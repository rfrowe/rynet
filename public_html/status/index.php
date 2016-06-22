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
                            error: serverError,
                            data: {id: server.name}
                        })
                    }
                }

                function populateServer(data, text, ajax) {

                }

                function serverError(ajax, text, error) {

                }
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
