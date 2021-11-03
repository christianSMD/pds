<?php
 die();
?>

09:33:26 ERROR: SQLSTATE[HY000] [1045] Access denied for user 'smdtecfu_psdztu'@'localhost' (using password: YES) in framework\base\router.class.php on line 2145, last called by framework\base\router.class.php on line 2103 through function connectByPDO.
 in framework\base\router.class.php on line 2196 when visiting 

09:33:26 __autoload() is deprecated, use spl_autoload_register() instead in framework\helper.class.php on line 173 when visiting 

09:41:06 ERROR: SQLSTATE[HY000] [1045] Access denied for user 'smdtecfu_psdztu'@'localhost' (using password: YES) in framework\base\router.class.php on line 2145, last called by framework\base\router.class.php on line 2103 through function connectByPDO.
 in framework\base\router.class.php on line 2196 when visiting 

09:41:06 __autoload() is deprecated, use spl_autoload_register() instead in framework\helper.class.php on line 173 when visiting 

09:41:09 ERROR: SQLSTATE[HY000] [1045] Access denied for user 'smdtecfu_psdztu'@'localhost' (using password: YES) in framework\base\router.class.php on line 2145, last called by framework\base\router.class.php on line 2103 through function connectByPDO.
 in framework\base\router.class.php on line 2196 when visiting 

09:41:09 __autoload() is deprecated, use spl_autoload_register() instead in framework\helper.class.php on line 173 when visiting 

09:41:22 ERROR: SQLSTATE[HY000] [1045] Access denied for user 'smdtecfu_psdztu'@'localhost' (using password: YES) in framework\base\router.class.php on line 2145, last called by framework\base\router.class.php on line 2103 through function connectByPDO.
 in framework\base\router.class.php on line 2196 when visiting 

09:41:22 __autoload() is deprecated, use spl_autoload_register() instead in framework\helper.class.php on line 173 when visiting 

09:42:02 ERROR: SQLSTATE[42S02]: Base table or view not found: 1146 Table 'pds_db.zt_team' doesn't exist&lt;p&gt;The sql is: SELECT root, account FROM `zt_team` wHeRe type  = 'project'&lt;/p&gt; in lib\base\dao\dao.class.php on line 1392, last called by lib\base\dao\dao.class.php on line 706 through function sqlError.
 in framework\base\router.class.php on line 2196 when visiting my

09:42:02 __autoload() is deprecated, use spl_autoload_register() instead in framework\helper.class.php on line 173 when visiting my

09:58:59 ERROR: SQLSTATE[42S02]: Base table or view not found: 1146 Table 'pds.zt_team' doesn't exist&lt;p&gt;The sql is: SELECT root, account FROM `zt_team` wHeRe type  = 'project'&lt;/p&gt; in lib\base\dao\dao.class.php on line 1392, last called by lib\base\dao\dao.class.php on line 706 through function sqlError.
 in framework\base\router.class.php on line 2196 when visiting my

09:58:59 __autoload() is deprecated, use spl_autoload_register() instead in framework\helper.class.php on line 173 when visiting my

09:59:13 ERROR: SQLSTATE[42S02]: Base table or view not found: 1146 Table 'pds.zt_team' doesn't exist&lt;p&gt;The sql is: SELECT root, account FROM `zt_team` wHeRe type  = 'project'&lt;/p&gt; in lib\base\dao\dao.class.php on line 1392, last called by lib\base\dao\dao.class.php on line 706 through function sqlError.
 in framework\base\router.class.php on line 2196 when visiting traffic

09:59:13 __autoload() is deprecated, use spl_autoload_register() instead in framework\helper.class.php on line 173 when visiting traffic

15:19:50 ERROR: SQLSTATE[42S22]: Column not found: 1054 Unknown column 't4.id' in 'field list'&lt;p&gt;The sql is: SELECT *, IF(INSTR(&quot; done,closed&quot;, status) &lt; 2, 0, 1) AS isDone, t4.id AS briefID FROM `zt_project` wHeRe iscat  = '0' AND  status IN ('') AND  deleted  = '0' oRdEr bY `order` desc &lt;/p&gt; in lib\base\dao\dao.class.php on line 1392, last called by lib\base\dao\dao.class.php on line 706 through function sqlError.
 in framework\base\router.class.php on line 2196 when visiting project-all-

15:19:50 __autoload() is deprecated, use spl_autoload_register() instead in framework\helper.class.php on line 173 when visiting project-all-

15:22:44 ERROR: SQLSTATE[23000]: Integrity constraint violation: 1052 Column 'status' in field list is ambiguous&lt;p&gt;The sql is: SELECT *, IF(INSTR(&quot; done,closed&quot;, status) &lt; 2, 0, 1) AS isDone, t4.id AS briefID FROM `zt_project` LEFT JOIN `c_zt_brief` AS t4  ON t2.id = t4.projectID  wHeRe iscat  = '0' AND  status IN ('') AND  deleted  = '0' oRdEr bY `order` desc &lt;/p&gt; in lib\base\dao\dao.class.php on line 1392, last called by lib\base\dao\dao.class.php on line 706 through function sqlError.
 in framework\base\router.class.php on line 2196 when visiting project-all-

15:22:44 __autoload() is deprecated, use spl_autoload_register() instead in framework\helper.class.php on line 173 when visiting project-all-

15:24:35 Uncaught Error: Call to undefined method sql::innerjoin() in lib\base\dao\dao.class.php:1027
Stack trace:
#0 module\project\model.php(749): baseDAO->__call('innerjoin', Array)
#1 module\project\model.php(857): projectModel->getList('', 0, 0, 0)
#2 module\project\ext\control\all.php(35): projectModel->getProjectStats('', 0, 0, 30, 'order_desc', Object(pager))
#3 framework\base\router.class.php(1691): myProject->all('', '3269', 'order_desc', 0, 0, 10, 1)
#4 www\index.php(68): baseRouter->loadModule()
#5 {main}
  thrown in lib\base\dao\dao.class.php on line 1027 when visiting project-all-

15:25:20 ERROR: SQLSTATE[42S22]: Column not found: 1054 Unknown column 't4.id' in 'field list'&lt;p&gt;The sql is: SELECT *, IF(INSTR(&quot; done,closed&quot;, status) &lt; 2, 0, 1) AS isDone, t4.id AS briefID FROM `zt_project` wHeRe iscat  = '0' AND  status IN ('') AND  deleted  = '0' oRdEr bY `order` desc &lt;/p&gt; in lib\base\dao\dao.class.php on line 1392, last called by lib\base\dao\dao.class.php on line 706 through function sqlError.
 in framework\base\router.class.php on line 2196 when visiting project-all-

15:25:20 __autoload() is deprecated, use spl_autoload_register() instead in framework\helper.class.php on line 173 when visiting project-all-

15:35:17 ERROR: SQLSTATE[23000]: Integrity constraint violation: 1052 Column 'status' in field list is ambiguous&lt;p&gt;The sql is: SELECT *, IF(INSTR(&quot; done,closed&quot;, status) &lt; 2, 0, 1) AS isDone FROM `zt_project` AS t2  LEFT JOIN `c_zt_brief` AS t4  ON t2.id = t4.projectID  wHeRe iscat  = '0' AND  status IN ('') AND  deleted  = '0' oRdEr bY `order` desc &lt;/p&gt; in lib\base\dao\dao.class.php on line 1392, last called by lib\base\dao\dao.class.php on line 706 through function sqlError.
 in framework\base\router.class.php on line 2196 when visiting project-all-

15:35:17 __autoload() is deprecated, use spl_autoload_register() instead in framework\helper.class.php on line 173 when visiting project-all-

15:36:17 ERROR: SQLSTATE[23000]: Integrity constraint violation: 1052 Column 'status' in where clause is ambiguous&lt;p&gt;The sql is: SELECT *, IF(INSTR(&quot; done,closed&quot;, t2.status) &lt; 2, 0, 1) AS isDone FROM `zt_project` AS t2  LEFT JOIN `c_zt_brief` AS t4  ON t2.id = t4.projectID  wHeRe iscat  = '0' AND  status IN ('') AND  deleted  = '0' oRdEr bY `order` desc &lt;/p&gt; in lib\base\dao\dao.class.php on line 1392, last called by lib\base\dao\dao.class.php on line 706 through function sqlError.
 in framework\base\router.class.php on line 2196 when visiting project-all-

15:36:17 __autoload() is deprecated, use spl_autoload_register() instead in framework\helper.class.php on line 173 when visiting project-all-

15:37:59 ERROR: SQLSTATE[23000]: Integrity constraint violation: 1052 Column 'deleted' in where clause is ambiguous&lt;p&gt;The sql is: SELECT *, IF(INSTR(&quot; done,closed&quot;, t2.status) &lt; 2, 0, 1) AS isDone FROM `zt_project` AS t2  LEFT JOIN `c_zt_brief` AS t4  ON t2.id = t4.projectID  wHeRe iscat  = '0' AND  t2.status IN ('') AND  deleted  = '0' oRdEr bY `order` desc &lt;/p&gt; in lib\base\dao\dao.class.php on line 1392, last called by lib\base\dao\dao.class.php on line 706 through function sqlError.
 in framework\base\router.class.php on line 2196 when visiting project-all-

15:38:00 __autoload() is deprecated, use spl_autoload_register() instead in framework\helper.class.php on line 173 when visiting project-all-

15:38:54 ERROR: SQLSTATE[23000]: Integrity constraint violation: 1052 Column 'order' in order clause is ambiguous&lt;p&gt;The sql is: SELECT *, IF(INSTR(&quot; done,closed&quot;, t2.status) &lt; 2, 0, 1) AS isDone FROM `zt_project` AS t2  LEFT JOIN `c_zt_brief` AS t4  ON t2.id = t4.projectID  wHeRe iscat  = '0' AND  t2.status IN ('') AND  t2.deleted  = '0' oRdEr bY `order` desc &lt;/p&gt; in lib\base\dao\dao.class.php on line 1392, last called by lib\base\dao\dao.class.php on line 706 through function sqlError.
 in framework\base\router.class.php on line 2196 when visiting project-all-

15:38:54 __autoload() is deprecated, use spl_autoload_register() instead in framework\helper.class.php on line 173 when visiting project-all-

15:52:37 ERROR: SQLSTATE[42S22]: Column not found: 1054 Unknown column 't2.projectID' in 'on clause'&lt;p&gt;The sql is: SELECT *, IF(INSTR(&quot; done,closed&quot;, t2.status) &lt; 2, 0, 1) AS isDone, t4.id AS briefID FROM `zt_project` AS t2  LEFT JOIN `c_zt_brief` AS t4  ON t2.projectID = t2.id  wHeRe iscat  = '0' AND  t2.status IN ('') AND  t2.deleted  = '0' oRdEr bY t2.`order` desc &lt;/p&gt; in lib\base\dao\dao.class.php on line 1392, last called by lib\base\dao\dao.class.php on line 706 through function sqlError.
 in framework\base\router.class.php on line 2196 when visiting project-all-

15:52:38 __autoload() is deprecated, use spl_autoload_register() instead in framework\helper.class.php on line 173 when visiting project-all-

16:34:35 ERROR: SQLSTATE[42S02]: Base table or view not found: 1146 Table 'pds.table_briefs' doesn't exist&lt;p&gt;The sql is: SELECT *, IF(INSTR(&quot; done,closed&quot;, status) &lt; 2, 0, 1) AS isDone, t4.id AS briefID FROM `zt_project` AS t2  LEFT JOIN TABLE_BRIEFS AS t4  ON t2.id = t4.projectID  wHeRe iscat  = '0' AND  deleted  = '0' oRdEr bY `isDone`,`status`,`order` desc &lt;/p&gt; in lib\base\dao\dao.class.php on line 1392, last called by lib\base\dao\dao.class.php on line 706 through function sqlError.
 in framework\base\router.class.php on line 2196 when visiting project-all--0-order_desc-0-136-10-3

16:34:35 __autoload() is deprecated, use spl_autoload_register() instead in framework\helper.class.php on line 173 when visiting project-all--0-order_desc-0-136-10-3

16:44:04 ERROR: SQLSTATE[42S02]: Base table or view not found: 1146 Table 'pds.table_briefs' doesn't exist&lt;p&gt;The sql is: SELECT *, IF(INSTR(&quot; done,closed&quot;, status) &lt; 2, 0, 1) AS isDone, t4.id AS briefID FROM `zt_project` AS t2  LEFT JOIN TABLE_BRIEFS AS t4  ON t2.id = t4.projectID  wHeRe iscat  = '0' AND  deleted  = '0' oRdEr bY `isDone`,`status`,`order` desc &lt;/p&gt; in lib\base\dao\dao.class.php on line 1392, last called by lib\base\dao\dao.class.php on line 706 through function sqlError.
 in framework\base\router.class.php on line 2196 when visiting project-all--0-order_desc-0-136-10-3

16:44:04 __autoload() is deprecated, use spl_autoload_register() instead in framework\helper.class.php on line 173 when visiting project-all--0-order_desc-0-136-10-3
