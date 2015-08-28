<?php
/**
 * 数据备份恢复操作类
 * @author reginx<reginx@qq.com>
 * $Id: Db.php 3096 2011-06-22 04:50:38Z cyp $
 */
class Model_Db extends Core_Model
{
    var $ver = NULL;

    /**
     * 构造函数
     *
     */
    function __construct(){
        parent::__construct();
        $this->ver     = Core_Db::server_info();
    }

    /**
     * 获取当前数据库下所有表信息 
     *
     * @param unknown_type $inf
     * @return unknown
     */
    function getAllTables($inf=0){
        $tables = array();
        $tmp = $this->getAll('show tables');
        foreach ($tmp as $v){
            if($inf){
                $tables[] = $this->getTableInfo(array_shift($v));
            }else{
                $tables[] = array_shift($v);
            }
        }
        return $tables;
    }

    /**
     * 备份表数据&结构
     *
     * @param unknown_type $tab
     * @param unknown_type $startfrom
     * @param unknown_type $currsize
     */
    function sqlDumpTableData($table, $startfrom = 0 ,$filesize = 2048, $backupdir = NULL,$fn=1) {
        $ret = array();
        $offset = 300;
        $filesize = intval($filesize * 1024 * 0.982);
        $ret['filesize'] = $filesize;
        if(empty($backupdir)){
            $ret['error'] = '备份目录不存在';
            return $ret;
        }
        if(!is_dir($backupdir)){ mkdir($backupdir,0755);}
        $ret['backupdir'] = realpath($backupdir);

        $this->query('SET SQL_QUOTE_SHOW_CREATE=0');
        $this->query('SET NAMES `UTF8`');
        $sql  = "select * from $table[name] limit $startfrom,$offset";
        $hasPk = isset($table['pk']);
        $sql = $hasPk?"select * from $table[name] where {$table['pk']} > $startfrom order by {$table['pk']} asc  limit $offset":$sql;

        $tableDump = "\n# <?php exit();?> "
        ."\n# Table: $table[name] "
        ."\n# Date: ".date('Y-m-d H:i',time())." \n\n";
        // 过滤Session 表
        if(preg_match('/base_session/i',$table['name'])){
            $ret['code'] = 'next';
        }else{
            $query = Core_Db::query($sql);
            while (((strlen($tableDump)) <= $ret['filesize']) && ($row = Core_Db::fetchArray($query))) {
                $tableDump .= "insert into $table[name] values (".implode(',',array_map(array('Model_Db','fieldSlashes'),$row)).");\n";
                $startfrom = $hasPk?$row[$table['pk']]:($startfrom+1);
            }
            $ret['fid'] = 0;
            $ret['start'] = $startfrom;
            if($hasPk){
                $ret['last'] = intval(Core_Db::getOne("select count(`{$table[fields][0]}`) from $table[name] where $table[pk] > $startfrom"));
            }else{
                $sql = "select `{$table[fields][0]}` from $table[name] limit $startfrom , $offset";
                $ret['last'] = intval(Core_Db::num_rows(Core_Db::query($sql)));
            }
            if($ret['last'] === 0){
                $ret['code'] = 'next';
            }

            $ret['fn'] = $fn;
            // write
            $dumpFile = $this->chkFile($ret['backupdir'].'/'.(date('ymd',time())).'_'.substr(md5($backupdir),0,6).'-'.$fn,$ret);

            @$fp = fopen($dumpFile.'.sql', 'a+');
            @flock($fp, 2);
            if(@!fwrite($fp, $tableDump)) {
                @fclose($fp);
                $ret['error'] = '写入备份文件失败!';
            } else {
                fclose($fp);
            }
        }
        return $ret;
    }


    /**
     * 获取下个备份表
     *
     * @param unknown_type $str
     * @param unknown_type $ret
     * @return unknown
     */
    function chkFile($str,&$ret){
        $file = $str.'.sql';
        if(!is_file($file)){ return $str; }
        clearstatcache();
        if(intval(filesize($file)) >= ($ret['filesize'])){
            $ret['fn']++;
            return preg_replace('/\-(\d+)/i',"-".$ret['fn'],$str);
        }
        return $str;

    }

    /**
     * 字段转义
     *
     * @param unknown_type $field
     * @return unknown
     */
    static function fieldSlashes($field){
        return "'".mysql_real_escape_string($field)."'";
    }
    /**
     * 获取表信息
     *
     * @param unknown_type $table
     * @return unknown
     */
    function getTableInfo($table){
        $ret = array();
        $query = Core_Db::query('SHOW FULL COLUMNS FROM '.$table);
        while ($row = Core_Db::fetchArray($query)){
            if($row['Extra'] == 'auto_increment'){
                $ret['pk'] = $row['Field'];
            }else{
                $ret['fields'][] = $row['Field'];
            }
        }
        $tableInfo = Core_Db::fetchArray(Core_Db::query('show create table '.$table),MYSQL_NUM);
        $ret['create'] = $tableInfo[1].";\n";
        $ret['drop']   = "DROP TABLE IF EXISTS $table;\n";
        if($this->ver > '4.1') {
            $ret['create'] = preg_replace("/(DEFAULT)*\s*CHARSET=.+/", "DEFAULT CHARSET=UTF8;",$ret['create']);
        }else if($this->ver < '4.1') {
            $ret['create'] = preg_replace("/TYPE\=(.+)/i", "ENGINE=\\1 DEFAULT CHARSET=UTF8;", $ret['create']);
        }
        $ret['create'] = preg_replace('/AUTO_INCREMENT=\d+/i','',$ret['create']);
        $ret['name'] = $table;
        return $ret;
    }

    /**
     * 表结构
     *
     * @param unknown_type $tables
     * @param unknown_type $start
     * @param unknown_type $filesize
     * @param unknown_type $backupdir
     */
    function sqlDumpTables($tables,$start,$filesize,$backupdir){
        $tableDump = "# <?php exit();?> ";
        $tpl = "\n\n# Table: %s "
        ."\n# Version: 2.0"
        ."\n# Date: ".date('Y-m-d H:i',time())." \n\n";
        foreach ($tables as $v){
            if(strpos($v['name'],'base_session')===false){
                $tableDump .= sprintf($tpl,$v['name']).$v['drop'].(($v['create']));
            }
        }
        if(!is_dir($backupdir)){ mkdir($backupdir,0755);}
        @file_put_contents($backupdir.'/'.(date('ymd',time())).'_'.substr(md5($backupdir),0,6).'-0.sql',$tableDump);

    }


    /**
     * 获取备份文件信息
     *
     * @return unknown
     */
    function getSQLFiles(){
        $ret = array();
        $backups = array_reverse(glob(realpath(ROOT.'data/').DIRECTORY_SEPARATOR.'*'));
        for ($j=0;$j<sizeof($backups);$j++){
            $dir = str_replace(realpath(ROOT).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR,'',$backups[$j]);
            $tmp = glob($backups[$j].DIRECTORY_SEPARATOR.'*.sql',GLOB_NOSORT);
            usort($tmp,array('Model_Db','sortFiles'));
            $ret[$dir]['size'] = 0;
            $ret[$dir]['num'] = sizeof($tmp);
            $ret[$dir]['date'] = date('Y-m-d H:i',filectime($backups[$j]));
            for ($i=0;$i<$ret[$dir]['num'];$i++){
                $temp = array();
                if(!isset($ret[$dir]['ver'])){
                    preg_match('/\#\s*version\:(.*)/i',file_get_contents($tmp[0]),$m);
                    $ret[$dir]['ver'] = $m[1];
                }
                $temp['size'] = sprintf("%.2f",floatval(filesize($tmp[$i]) / 1024));
                $temp['date'] = date('Y-m-d H:i',filemtime($tmp[$i]));
                $temp['file']     = str_replace(realpath($backups[$j]).'\\','',$tmp[$i]);
                $ret[$dir]['files'][] = $temp;
                $ret[$dir]['size'] += $temp['size'];
            }
        }
        return $ret;
    }

    /**
     * SQL文件排序
     *
     * @param unknown_type $a
     * @param unknown_type $b
     * @return unknown
     */
    function sortFiles($a,$b){
        $a = intval(array_pop(explode('-',$a)));
        $b = intval(array_pop(explode('-',$b)));
        if($a === $b){ return 0; }
        return $a>$b?1:-1;
    }

    /**
     * 删除备份文件 
     * 
     */
    function unlinkFiles($arr){
        if(!is_array($arr)){ return FALSE; }
        foreach ($arr as $v){
            if($v = realpath(ROOT.'/data/'.$v.'/')){
                if(preg_match('/backup_[0-9a-z]{32}/i',$v)){
                    $this->removeDir($v);
                }
            }
        }
        return TRUE;
    }

    /**
     * 删除指定目录下所有内容
     *
     * @param unknown_type $path
     * @return unknown
     */
    function removeDir($path) {
        if(trim($path) == ''){ return FALSE;}
        if (substr($path, -1, 1) != "/") {
            $path .= "/";
        }
        $normal_files = glob($path . "*");
        $hidden_files = glob($path . "\.?*");
        $all_files = array_merge($normal_files, $hidden_files);
        foreach ($all_files as $file) {
            if (preg_match("/(\.|\.\.)$/", $file)){ continue; }
            if (is_file($file) === TRUE) {
                unlink($file);
            }else if (is_dir($file) === TRUE) {
                $this->removeDir($file);
            }
        }
        if (is_dir($path) === TRUE) { rmdir($path); }
    }

    /**
     * 恢复
     *
     * @param unknown_type 目录
     * @param unknown_type 文件编号
     * @param unknown_type 读取位置(Line)
     * @return unknown
     */
    function restore($dir,$fid=0,$pos=0){
        set_time_limit(0);
        $ret = array();
        $ret['dir'] = $dir;
        $ret['pos'] = $pos;
        $read = 1000;
        $files = glob($dir.DIRECTORY_SEPARATOR.'*.sql',GLOB_NOSORT);
        usort($files,array('Model_Db','sortFiles'));
        $ret['file'] = str_replace($dir.'\\','',$files[$fid]);
        //恢复数据库结构
        if($fid === 0){
            $str = file_get_contents($files[$fid]);
            if(trim($str) != '' && $str != FALSE){
                $str = preg_replace('/\#.*\s+/i','',$str);
                $str = preg_replace('/\s+/i',' ',$str);
                $str = explode(';',$str);
                foreach ($str as $v){
                    if(trim($v)!=''){
                        Core_Db::query($v);
                    }
                }
            }
            $str = NULL;unset($str);
            $ret['fid'] = $fid+1;
            $ret['pos'] = 0;
        }else{
            $fp = fopen($files[$fid], "r");
            for($i=1;$i<=$pos;$i++){
                if(feof($fp)){break;}
                $tb = fgets($fp,4096);
            }
            $c = 0;
            while (($buf = fgets($fp, 4096)) && ($c < $read)) {
                $buf = preg_replace('/^\s+\#.*\s+/i','',$buf);
                //$buf = preg_replace('/\s+/i',' ',$buf);
                if($buf != "\n" && trim($buf) != ''){
                    Core_Db::query($buf);
                }
                $c++;
            }
            $ret['fid']  = feof($fp)?$fid+1:$fid;
            $ret['pos']  = feof($fp)? 0 : ( $ret['pos'] + $read );
            $ret['done'] = !isset($files[$ret['fid']]);
        }

        return $ret;
    }
}
?>