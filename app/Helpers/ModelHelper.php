<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class ModelHelper
{
    private static $operators = [
        "\$gt" => ">",
        "\$gte" => ">=",
        "\$lte" => "<=",
        "\$lt" => "<",
        "\$like" => "like",
        "\$like" => "like",
        "\$not" => "<>",
        "\$in" => "in"
    ];

    public static function select($schema, $request = null, $class) 
    {
        $_select = [];
        foreach(array_values($schema) as $select) {
        	if (isset($select['is_raw']) && $select['is_raw']) {
        		$_select[] = DB::raw($select['column'] . ' as '. $select['alias']);
        	} else {
        		$_select[] = $select['column'] . ' as '. $select['alias'];
        	}
        }

        return $class::select($_select);
    }

    public static function join($schema, $request = null, $model) 
    {
        foreach($schema as $join) {
            if ($join['type'] == 'left') {

            	if (count($join['on']) < 3) {
            		$model->leftJoin($join['table'], function($q) use ($join) {
            			foreach ($join['on'] as $single_join) {
            				$q->on($single_join[0], $single_join[1], DB::raw("'".$single_join[2]."'"));
            			}
				    });
            	} else {
            		$model->leftJoin($join['table'], [$join['on']]);
            	}
                
            } else {
                $model->join($join['table'], [$join['on']]);
            }
        }
    }

	public static function dynamicFilterAnd($params, $request, $model, $class)
	{
        foreach (array($params) as $k => $v) {
            foreach (array_keys($v) as $key => $row) {
                if (isset($class::mapSchema()['field'][$row])) {
                    if (is_array(array_values($v)[$key])) {
                        if (count(array_values($v)[$key]) > 0) {
                            foreach(array_values($v)[$key] as $keyOpr => $valOpr) {
                                if (self::$operators[$keyOpr] != 'like') {
                                	if (self::$operators[$keyOpr] == '<>' && $valOpr == 'null') {
                                		$model->whereNotNull($class::mapSchema()['field'][$row]['column']);
                                		$model->where($class::mapSchema()['field'][$row]['column'], '!=', '');
                                	} else {
                                		$model->where($class::mapSchema()['field'][$row]['column'], self::$operators[$keyOpr], $valOpr);
                                	}
                                } else {
                                    $model->where($class::mapSchema()['field'][$row]['column'], 'like', '%'.$valOpr.'%');
                                }
                            }
                        }
                    } else {
                        if ($class::mapSchema()['field'][$row]['type'] === 'int') {
                            $model->where($class::mapSchema()['field'][$row]['column'], array_values($v)[$key]);
                        } else {
                            $model->where($class::mapSchema()['field'][$row]['column'], 'like', '%'.array_values($v)[$key].'%');
                        }
                    }
                }
            }
        }
	}

	public static function dynamicFilterOr($params, $request, $model, $class)
	{
		$n = 0;
		$comparison_total = -1;

	    foreach($params as $orKey => $orVal) {
	        if (isset($class::mapSchema()['field'][$orKey])) {
	        	$explode_if_got_separator = explode('||', $orVal);
	        	foreach ($explode_if_got_separator as $val) {
	        		$comparison_total += 1;
	        	}
	        }
	    }

	    foreach($params as $orKey => $orVal) {
	        if (isset($class::mapSchema()['field'][$orKey])) {
	        	$explode_if_got_separator = explode('||', $orVal);
	        	foreach ($explode_if_got_separator as $val) {
		            if ($class::mapSchema()['field'][$orKey]['type'] === 'int') {
		                if ($n < 1) {
		                    $model->whereRaw('( '.$class::mapSchema()['field'][$orKey]['column'] . ' = \'' .$val.'\'');
		                } else if ($n > 0 && $n < $comparison_total) {
		                    $model->orWhereRaw($class::mapSchema()['field'][$orKey]['column'] . ' = \''.$val.'\'');
		                } else {
		                    $model->orWhereRaw($class::mapSchema()['field'][$orKey]['column'] . ' = \'' .$val.'\' )');
		                }
		            } else {
		                if ($n < 1) {
		                    $model->whereRaw('( '.$class::mapSchema()['field'][$orKey]['column'] . ' like \'%'.$val.'%\'');
		                } else if ($n > 0 && $n < $comparison_total) {
		                    $model->orWhereRaw($class::mapSchema()['field'][$orKey]['column'] . ' like \'%'.$val.'%\'');
		                } else {
		                    $model->orWhereRaw($class::mapSchema()['field'][$orKey]['column'] . ' like \'%'.$val.'%\')');
		                }
		            }
		            $n++;
	        	}
	        }
	    }
	}
	
	public static function generateAllResults($schema, $params, $request, $model, $append = [])
	{
		if (isset($params['order']) && is_array($params['order'])) {
			foreach ($params['order'] as $orderKey => $orderVal) {
				$model->orderBy($schema['field'][$orderKey]['column'], $orderVal);
			}
		}

		$data = $model->get();

		return self::response($data, false);
	}

	public static function generatePagingResults($schema, $page, $params, $request, $model, $append = [])
	{
		$per_page = 10;

		if (isset($params['order']) && is_array($params['order'])) {
			foreach ($params['order'] as $orderKey => $orderVal) {
				$model->orderBy($schema['field'][$orderKey]['column'], $orderVal);
			}
		}

		if (isset($params['per_page']) && $params['per_page'] > 0) {
			$per_page = $params['per_page'];
		}
		
        $countAll = $model->count();
        $currentPage = $page > 0 ? $page - 1 : 0;
        $page = $page > 0 ? $page + 1 : 2; 
        $nextPage = $request->url().'?page='.$page;
        $prevPage = $request->url().'?page='.($currentPage < 1 ? 1 : $currentPage);
        $totalPage = ceil((int)$countAll / $per_page);

        $model->skip($currentPage * $per_page)
           ->take($per_page);
		
		$data = $model->get();
		
		$results['totalData'] = $countAll;
		$results['nextPage'] = $nextPage;
		$results['prevPage'] = $prevPage;
		$results['totalPage'] = $totalPage;
		$results['data'] = $data;

		return self::response($results, true);
	}

	public static function response($params, $is_paging)
	{
		$results = $params;

		if ($is_paging) {
			$results = [
	            'nav' => [
	                'totalData' => $params['totalData'],
	                'nextPage' => $params['nextPage'],
	                'prevPage' => $params['prevPage'],
	                'totalPage' => $params['totalPage']
	            ],
	            'data' => $params['data']
			];
		}

		return $results;
	}

	public static function adjustSequencePostgreSql()
	{
        $tables = DB::select(DB::raw("SELECT table_name FROM information_schema.tables WHERE table_schema='public' AND table_type='BASE TABLE'"));

        foreach ($tables as $table) {
            $primary_key = DB::select(DB::raw("SELECT               
              pg_attribute.attname, 
              format_type(pg_attribute.atttypid, pg_attribute.atttypmod) 
            FROM pg_index, pg_class, pg_attribute, pg_namespace 
            WHERE 
              pg_class.oid = '".$table->table_name."'::regclass AND 
              indrelid = pg_class.oid AND 
              nspname = 'public' AND 
              pg_class.relnamespace = pg_namespace.oid AND 
              pg_attribute.attrelid = pg_class.oid AND 
              pg_attribute.attnum = any(pg_index.indkey)
             AND indisprimary"));

            if ($table->table_name && isset($primary_key[0]->attname)) {
                $sequence_name = DB::select(DB::raw("SELECT * FROM information_schema.sequences WHERE sequence_name = '".$table->table_name."_".$primary_key[0]->attname."_seq' "));
                if (isset($sequence_name[0]->sequence_name)) {
                    DB::select(DB::raw("SELECT SETVAL('".$sequence_name[0]->sequence_name."', (SELECT MAX(".$primary_key[0]->attname.") + 1 FROM ".$table->table_name."))"));
                }
            }
        }
	}
}