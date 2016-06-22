<?php

require($_SERVER["DOCUMENT_ROOT"]."/utils/UserUtils.php");
$userutils = new UserUtils();
$userutils->secure();

?>
<html>
    <head>
        <title>rynet status</title>
        <?php readfile($_SERVER["DOCUMENT_ROOT"]."/utils/imports.php") ?>
        <script>
            $(function() {
                $.ajax("/utils/servers.json", {
                    dataType: 'json',
                    success: populateGrid
                });

                function populateGrid(data) {
                    console.log("fuck");
                    var $statusGrid = $("#status_grid");
                    for(var i = 0; i < data.length; i++) {
                        var server = data[i];
                        var $section = $("<section id=\"" + server.name + "\"></section>");
                        $section.append(
                            "<aside> \
                                <div><img src=\"/images/" + server.icon + ".svg\" alt=\"" + server.icon + "\" /></div> \
                                <h3>" + server.name + "</h3> \
                             </aside>");
                        $statusGrid.append($section);

                        $.ajax(server.url, {
                            dataType: 'json',
                            success: populateServer,
                            error: function() {
                                $section.addClass("bad");
                            },
                        })
                    }
                }

                function populateServer(data, text, ajax) {
                    var $section = $("#" + data.id);
                    var $table = $("<table></table>");
                    var status = true;
                    $.each(data, function(i, val) {
                        var $row = $("<tr></tr>");
                        if (i != "id") {
                            if (isBoolean(val)) {
                                var $td = $("<td colspan='2'></td>");
                                var $subsection = $("<section></section>");
                                $subsection.append("<aside> \
                                                    <h3>" + i + "</h3> \
                                                    </aside>");
                                $subsection.addClass(val ? "good" : "bad");
                                $td.append($subsection);
                                $row.append($td);
                            } else {
                                if (i == "Uptime") {
                                    val = secondsToHms(val);
                                }
                                $row.append("<td>" + i + "</td>");
                                $row.append("<td>" + val + "</td>");
                            }
                        }
                        $table.append($row);
                    });
                    $section.addClass(status ? "good" : "unknown");
                    $section.append($table);

                    // Fix subsection borders
                    $("td[colspan=2]").parent().css("border-bottom", "none");
                }

                function secondsToHms(d) {
                    d = Number(d);
                    var h = Math.floor(d / 3600);
                    var m = Math.floor(d % 3600 / 60);
                    var s = Math.floor(d % 3600 % 60);
                    return ((h > 0 ? h + " hours, " + (m < 10 ? "0" : "") : "") + m + " minutes, " + (s < 10 ? "0" : "") + s + " seconds");
                }

                function isBoolean(variable) { return variable === true || variable === false; }
            });
        </script>
    </head>
    <body>
        <?php include($_SERVER["DOCUMENT_ROOT"]."/utils/header.php") ?>
        <main>
            <h2>Welcome, <?php echo $userutils->name() ?></h2>
            <section id="status_grid">
                <!--<section class="good">
                    <aside>
                        <div><img src="/images/ubuntu.svg" alt="ubuntu" /></div>
                        <h3>Multivac</h3>
                    </aside>
                    <table>
                        <tr>
                            <td>IP</td>
                            <td>23.94.134.251</td>
                        </tr>
                        <tr>
                            <td>OpenVPN</td>
                            <td>Running</td>
                        </tr>
                        <tr>
                            <td>NUC</td>
                            <td>Connected</td>
                        </tr>
                        <tr>
                            <td>NUC IP</td>
                            <td>10.8.0.6</td>
                        </tr>
                    </table>
                </section>
                <section class="bad">
                    <aside>
                        <div><img src="/images/intel.svg" alt="intel" /></div>
                        <h3>NUC</h3>
                    </aside>
                    <table>
                        <tr>
                            <td>eth0</td>
                            <td>10.42.0.1</td>
                        </tr>
                        <tr>
                            <td>eth1</td>
                            <td>192.168.1.69</td>
                        </tr>
                        <tr>
                            <td>tun0</td>
                            <td>10.8.0.6</td>
                        </tr>
                        <tr>
                            <td>OpenVPN</td>
                            <td>Connected</td>
                        </tr>
                        <tr>
                            <td>HAProxy</td>
                            <td>Running</td>
                        </tr>
                        <tr>
                            <td>Apache</td>
                            <td>Running</td>
                        </tr>
                        <tr>
                            <td>Plex</td>
                            <td>Running</td>
                        </tr>
                    </table>
                </section>-->
            </section>
        </main>
    </body>
</html>
