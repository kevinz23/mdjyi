<!DOCTYPE html>
<?php
$PAGEMAX = 100;

include 'mysqlconnect.php';
$params = array(
    'hostname' => 'localhost',
    'dbname' => 'mdjyi',
    'username' => 'root',
    'password' => ''
);
$connect = new mysqlconnect($params);
$connect->connect();

$filter = '';
$typeid = intval($_GET['typeid']);
if ($typeid != 0)
    $filter = 'where type_id=' . $typeid;

$pager = intval($_GET['pager']);
$pager = $pager == 0 ? 1 : $pager;

$type = $connect->fetch('select * from info_type');

$data = $connect->fetch("select info.id,title,content,insert_time,type_name,info_level 
                            from info join info_type on info.type_id=info_type.id {$filter} order by info_level,insert_time desc,id desc limit ?,?", array(($pager - 1) * $PAGEMAX, $PAGEMAX));
$infonum = $connect->fetch("select count(info.id) num from info join info_type on info.type_id=info_type.id {$filter}");
$infonum = $infonum[0]['num'];

$pagenum = round(0.4999 + $infonum / $PAGEMAX);

function param_url($typeid, $delta) {
    return ($typeid === 0) ? '?pager=' . $delta : '?typeid=' . $typeid . '&pager=' . $delta;
}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>牡丹江易网，招聘易，找工作易，租房易，买房易</title> 
        <META name="keywords" content="牡丹江易网，招聘易，找工作易，租房易，买房易">
        <META name="description" content="牡丹江易网，招聘易，找工作易，租房易，买房易">
        <link rel="stylesheet" type="text/css" href="http://www.0453.com//">
        <link rel="shortcut icon" href="favicon.ico">
    </head>

    <body background="http://www.0453.com/images/background.gif" topmargin="0" leftmargin="0">
        <div align="center">
            <table border="0" width="950" cellspacing="0" cellpadding="0" height="120">
                <tr>
                    <td width="260" align="center" valign="top">
                        <div style="margin-bottom: 0; margin-top:3px"><a href="/"><img border="0" src="http://temp.im/225x70" alt="牡丹江易网" width="255" height="70"></a></div>
                    </td>
                    <td width="570">
                    </td>
                </tr>
            </table>
        </div>
        <table border="0" width="948" cellspacing="0" cellpadding="0" style="word-break:break-all" align="center">
            <tr>
                <td width="8"><img border="0" src="http://0453.com/images/table_corner_t_l.gif" width="8" height="8"></td>
                <td width="932" background="http://0453.com/images/table_bg_t.gif"></td>
                <td width="8"><img border="0" src="http://0453.comimages/table_corner_t_r.gif" width="8" height="8"></td>
            </tr>
            <tr>
                <td background="http://0453.com/images/table_bg_l.gif" width="8"></td>
                <td width="932" style="word-break:break-all" bgcolor="#E6F7FF">
                    <table border="0" width="100%" cellspacing="1" cellpadding="0" bgcolor="#FFFFFF" align="center">
                        <?php
                        $typenum = count($type);
                        for ($i = 0; $i < $typenum / 19; $i++) {
                            ?>
                            <tr>
                                <?php if ($i == 0) { ?>
                                    <td width="5%" height="27" align="center" bgcolor="#E6F7FF"><a href="/"><font style="font-size:14px">首页</font></a></td>
                                <?php } for ($j = 0; $j < 19; $j++) { ?>
                                    <td width="5%" height="27" align="center" bgcolor="#E6F7FF"><a href="index.php?typeid=<?php echo $type[$i * 19 + $j]['id'] ?>"><font style="font-size:14px"><?php echo $type[$i * 19 + $j]['type_name'] ?></font></a></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </table>
                </td>
                <td background="http://0453.com/images/table_bg_r.gif" width="8"></td>
            </tr>
            <tr>
                <td><img border="0" src="http://0453.com/images/table_corner_b_l.gif" width="8" height="8"></td>
                <td background="http://0453.com/images/table_bg_b.gif" width="737"></td>
                <td><img border="0" src="http://0453.com/images/table_corner_b_r.gif" width="8" height="8"></td>
            </tr>
        </table>
        <table border="0" width="950" cellspacing="0" cellpadding="0" align="center">
            <tr>
                <td width="100%" height="5"></td>
            </tr>
        </table>

        <table border="0" width="950" cellspacing="0" cellpadding="0" align="center">
            <tr><td width="100%" style="background-image: url('http://0453.com/images/dot_line.gif')" height="1"></td></tr>                                                     
            <tr>                                                      
                <td width="100%" height="30" bgcolor="#D2F0FF">                                                     
                    <p align='center'>第<b><?php echo $pager ?></b>/<b><?php echo $pagenum ?></b>页&nbsp; 共<b><?php echo $infonum ?></b>条&nbsp; &nbsp; 
                        <a href='<?php echo param_url($typeid, $pager - 1) ?>'>&lt;&lt;</a>&nbsp;
                        <?php
                        for ($i = $pager - 5 < 1 ? 1 : $pager - 5; $i < $pager; $i++)
                            echo '<a href=' . param_url($typeid, $i) . '>[' . ($i) . ']</a>&nbsp;';
                        echo '<font color=red>[' . ($pager) . ']</font>&nbsp;';
                        $lastpage = $pager + 10 > $pagenum ? $pagenum : $pager + 10;
                        for ($i = $pager + 1; $i <= $lastpage; $i++) {
                            echo '<a href=' . param_url($typeid, $i) . '>[' . ($i) . ']</a>&nbsp;';
                        }
                        ?>
                        <a href='<?php echo param_url($typeid, $pager + 1 > $pagenum ? $pagenum : $pager + 1) ?>'>&gt;&gt;</a><a href=default.asp?pageid=186 ></a></p>                                                  
                </td>                                                                                 
            </tr>                                                       
        </table>               
        <?php
        $i = 0;
        while ($i < $PAGEMAX) {
            $col = 100;
            $col_width = intval(100 / intval($data[$i]['info_level']));
            
            ?>
            <table border=0 width=950 cellpadding=0 cellspacing=4 bgcolor=#FFFFFF align=center>
                <tr>
                    <?php while ($col > 4) { ?>
                        <td width='<?php echo $col_width ?>%' valign='top' bgcolor='#E8E8E8'>
                            <table border='0' width='100%' cellpadding='2' cellspacing='3'>
                                <tr>
                                    <td width="100%" height='25' class='adtype' bgcolor='#98DC98' align='center'>
                                        <font style='font-size:14px' color='#CC0000'><b><?php echo $data[$i]['title'] ?></b></font>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" height='133' valign='top' class='adtype' bgcolor='#E8F7E8'><?php echo $data[$i]['content'] ?></td>
                                </tr>
                                <tr>
                                    <td width='100%'><p align='center'><font color='#0066CC'>日期：<?php echo $data[$i]['insert_time'] ?></font></p></td>
                                </tr>    
                            </table>
                        </td>
                        <?php
                        $col-= $col_width;
                        $i++;
                    }
                    ?>  
                </tr>
            </table>
        <?php } ?>


        <table border="0" width="950" cellspacing="0" cellpadding="0" align="center">
            <tr><td width="100%" style="background-image: url('http://0453.com/images/dot_line.gif')" height="1"></td></tr>                                                     
            <tr>                                                      
                <td width="100%" height="30" bgcolor="#D2F0FF">                                                     
                    <p align='center'>第<b><?php echo $pager ?></b>/<b><?php echo $pagenum ?></b>页&nbsp; 共<b><?php echo $infonum ?></b>条&nbsp; &nbsp; 
                        <a href='<?php echo param_url($typeid, $pager - 1) ?>'>&lt;&lt;</a>&nbsp;
                        <?php
                        for ($i = $pager - 5 < 1 ? 1 : $pager - 5; $i < $pager; $i++)
                            echo '<a href=' . param_url($typeid, $i) . '>[' . ($i) . ']</a>&nbsp;';
                        echo '<font color=red>[' . ($pager) . ']</font>&nbsp;';
                        for ($i = $pager + 1; $i <= $lastpage; $i++) {
                            echo '<a href=' . param_url($typeid, $i) . '>[' . ($i) . ']</a>&nbsp;';
                        }
                        ?>
                        <a href='<?php echo param_url($typeid, $pager + 1 > $pagenum ? $pagenum : $pager + 1) ?>'>&gt;&gt;</a><a href=default.asp?pageid=186 ></a></p>                                                  
                </td>                                                                                
            </tr>                                                       
        </table>                                                                                                         
        <p style="margin-bottom: 3px" align="center"> </p>

        <script language="javascript" src="images/bottom.js"></script>                                                       
    </body>                                                                                                                          
</html>
