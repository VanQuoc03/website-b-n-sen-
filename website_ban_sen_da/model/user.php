<?php
function checkuser($user, $pass)
{
    $connect = connectdb();
    $stmt = $connect->prepare("SELECT * FROM user WHERE user = '" . $user . "' AND password = '" . $pass . "'");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $kq = $stmt->FetchALL();
    if (count($kq) > 0)
        return $kq[0]['role'];
    else return 0;
}
