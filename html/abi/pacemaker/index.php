<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
<?php include "../../header.html" ?>
        <title>Cluster Labs - Packages for Pacemaker 1.0.x</title>
        <meta name="description" content="">
     </head>
     <body>
<?php include "../../banner.html" ?>

     <section id="main">
<p>
This page details the ABI compatability between the listed
<a href="http://wiki.clusterlabs.org/wiki/Pacemaker">Pacemaker</a> versions.
The reports are generated with the
<a href="http://forge.ispras.ru/projects/abi-compliance-checker">ABI Compliance
Checker</a> that ships with <a href="http://fedoraproject.org">Fedora</a>
</p>

<table align=center style="width:75%;" cellpadding=1>
<caption>
  <a name="ABI_table" id="ABI_table"></a><h3>ABI Compatability Table</h3>
</caption>
 <tr>
  <th>Version</th>
  <th>Reference Version</th>
  <th>Status</th>
  <th>Report</th>
 </tr>

<?php
  function sorter($a, $b) {
    return version_compare($b["from"], $a["from"]);
  }

  $i = 0;
  $compat_reports = array();

  foreach (glob("*/compat_report.html") as $item) {
    $report = dirname($item);
    $v = explode("_", $report);
    if ((count($v) != 3) || ($v[1] != "to")) {
      continue;
    }

    $compat_reports[$i] = array();
    $compat_reports[$i]["filename"] = $item;
    $compat_reports[$i]["report"] = $report;
    $compat_reports[$i]["from"] = $v[0];
    $compat_reports[$i]["to"] = $v[2];

    $compat_reports[$i]["status"] = "unknown";
    $file_handle = fopen($compat_reports[$i]["filename"], "r");
    while (!feof($file_handle)) {
	$line = fgets($file_handle);
	if (strstr($line, "Verdict")) {
	    $compat_reports[$i]["status"] = preg_replace("/.*<td>/", "", $line);
	    $compat_reports[$i]["status"] = preg_replace("/<\/td>.*/", "", $compat_reports[$i]["status"]);
	    break;
	}
    }
    fclose($file_handle);    

    ++$i;
  }

  usort($compat_reports, "sorter");

  foreach ($compat_reports as $item) {
    $report = $item["report"];
    $filename = $item["filename"];
    $from = $item["from"];
    $to = $item["to"];
    $status = $item["status"];

    echo " <tr align=center>";
    echo "  <td> $to </td>";
    echo "  <td> $from </td>";
    echo "  <td> $status </td>";
    echo "  <td><a href=\"$filename\">report</a></td>";
    echo " </tr>";
  }
?>

</table>

     </section>  <!-- id="main" -->
     
<?php include "../../footer.html" ?>
    </body>
</html>
