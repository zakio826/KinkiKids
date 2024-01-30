<?php
//SQL実行チェック関数
function sql_check($stmt, $db) {
    //SQLが正しくない場合はエラーを表示
    if (!$stmt) :
        die($db->error);
    endif;

    //正しければSQL実行
    $success = $stmt->execute();

    //実行されなかったらエラー表示
    if (!$success) :
        die($db->error);
    endif;
}

//htmlspecialchars短縮関数
function h($value) {
    return htmlspecialchars($value);
}


//絞り込み検索タイトルキーワード
function add_sql_title($value) {
    if ($value != null) {
        $wildcard_val = "%" . $value . "%";
        return " AND records.title LIKE '{$wildcard_val}'";
    }
}

//絞り込み検索選択項目
function add_sql_item($column, $value) {
    if ($value !== null && $value !== "0") {
        return " AND {$column} = {$value} ";
    }
}

function insert_help($db, $mission, $child_id) {
    $sql = 'INSERT INTO points (date, help_id, title, family_id, child_id, input_time, point)
                        VALUES(?, ?, ?, ?, ?, ?, ?)';
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sisiisi", $mission->today, $mission->id, $mission->title, $mission->family_id, $child_id, $mission->input_time, $mission->point);
}

function select($db, $columns, $table, $joins = null, $wheres = null, $limits = null, $group_order = null) {
    $join = null;
    $where = null;
    $limit = null;
    $group = null;
    $order = null;
    $return = array();
    $col = array();

    if (count($columns) > 1) {
        if (array_values($columns) === $columns) {
            $column = implode(", ", $columns);
        } else {
            $column = implode(", ", array_keys($columns));
            foreach ($columns as $key => $value) {
                if (is_null($value)) {
                    if (preg_match("/\./", $key)) {
                        $col[] = preg_replace("/(\w+)\./", "", $key);
                    }
                } else {
                    $col[] = $value;
                }
            }
         }
    } else {
        if (array_values($columns) === $columns) {
            $column = $columns[0];
        } else {
            $column = array_keys($columns)[0];
            foreach ($columns as $key => $value) {
                if (is_null($value)) {
                    if (preg_match("/\./", $key)) {
                        $col[] = preg_replace("/(\w+)\./", "", $key);
                    } else {
                        $col[] = $key;
                    }
                } else {
                    $col[] = $value;
                }
            }
        }
    }

    if (is_array($joins) && count($joins) > 0) {
        foreach ($joins as $key => $value) {
            $join .= "\nLEFT JOIN " . $key . " ON " . $value;
        }
    }

    if (is_array($wheres) && count($wheres) > 0) {
        $where .= " WHERE ";
        foreach ($wheres as $key => $value) {
            if ($key === array_key_first($wheres)) {
                $where .= $key . " " . $value[0] . " ?";
            } else {
                if (isset($value[3]) && $value[3]) {
                    $where .= " OR " . $key . " " . $value[0] . " ?";
                } else {
                    $where .= " AND " . $key . " " . $value[0] . " ?";
                }
            }
        }
    }

    if (isset($limits)) {
        $limit = " LIMIT ";
        if (is_array($limits)) {
            $limit .=  $limits[0];
            if (count($limits) == 2) {
                $limit .= ", " . $limits[1];
            }
        } else {
            $limit .= $limits;
        }
    }

    if (isset($group_order)) {
        if (isset($group_order["group"])) {
            $group = " GROUP BY " . $group_order["group"];
        }

        if (isset($group_order["order"])) {
            $order = " ORDER BY " . $group_order["order"][0];
            if (isset($group_order["order"][1]) && $group_order["order"][1]) {
                $order .= " desc";
            } else {
                $order .= " asc";
            }
        }
    }

    $sql = "SELECT " . $column . " FROM " . $table . $join . $where . $group . $order . $limit;

    $stmt = $db->prepare($sql);
    if (is_array($wheres) && count($wheres) > 0) {
        $param = "";
        $bind = array();
        foreach ($wheres as $key => $value) {
            $param .= $value[1];
            $bind[] = $value[2];
        }
        $stmt->bind_param($param, ...$bind);
    }
    sql_check($stmt, $db);
    $result = $stmt->get_result();
    $num = 0;
    while ($row = $result->fetch_array(MYSQLI_NUM)) {
        for ($i = 0; $i < count($columns); $i++) {
            if (array_values($columns) === $columns) {
                $return[$num][$columns[$i]] = $row[$i];
            } else {
                $return[$num][$col[$i]] = $row[$i];
            }
        }
        $num++;
    }
    $stmt->close();
    return $return;
}

function insert($db, $data, $table) {
    $columns = implode(", ", array_keys($data));
    $values = array();
    $param = null;
    $insert = null;

    foreach ($data as $key => $value) {
        if ($key === array_key_first($data)) {
            $insert .= "?";
        } else {
            $insert .= ", ?";
        }
        $param .= $value[0];
        $values[] = $value[1];
    }

    $sql = "INSERT INTO " . $table . " (" . $columns . ") VALUES(" . $insert . ")";
    $stmt = $db->prepare($sql);
    $stmt->bind_param($param, ...$values);
    sql_check($stmt, $db);
}

function update($db, $data, $table, $wheres, $limits = null, $order = null) {
    $update = null;
    $where = null;
    $param = null;
    $limit = null;
    $bind = array();

    foreach ($data as $key => $value) {
        if ($key === array_key_first($data)) {
            $update .= $key . " = ?";
        } else {
            $update .= ", " . $key . " = ?";
        }
        $param .= $value[0];
        $bind[] = $value[1];
    }

    if (is_array($wheres) && count($wheres) > 0) {
        $where .= " WHERE ";
        foreach ($wheres as $key => $value) {
            if ($key === array_key_first($wheres)) {
                $where .= $key . " " . $value[0] . " ?";
            } else {
                $where .= " AND " . $key . " " . $value[0] . " ?";
            }
        }
    }

    if (isset($limits)) {
        $limit = " LIMIT ";
        if (is_array($limits)) {
            $limit .=  $limits[0];
            if (count($limits) == 2) {
                $limit .= ", " . $limits[1];
            }
        } else {
            $limit .= $limits;
        }
    }

    if (isset($order)) {
        $order = " ORDER BY " . $order[0];
        if (isset($order[1]) && $order[1]) {
            $order .= " DESC";
        } else {
            $order .= " ASC";
        }
    }

    $sql = "UPDATE " . $table . " SET " . $update . $where . $order . $limit;

    $stmt = $db->prepare($sql);
    if (is_array($wheres) && count($wheres) > 0) {
        foreach ($wheres as $key => $value) {
            $param .= $value[1];
            $bind[] = $value[2];
        }
        $stmt->bind_param($param, ...$bind);
    }

    sql_check($stmt, $db);
}

function ua_smt() {
    $ua = $_SERVER['HTTP_USER_AGENT'];
    $ua_list = array('iPhone', 'iPad', 'iPod', 'Android');
    foreach ($ua_list as $ua_smt) {
        if (strpos($ua, $ua_smt) !== false) {
            return true;
        }
    }
    return false;
}