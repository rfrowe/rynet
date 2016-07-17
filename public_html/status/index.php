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
                            context: {
                                section: $section
                            },
                            error: function() {
                                $(this.section).addClass("bad");
                            },
                        });
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
                                if(!val) {
                                    status = false;
                                }
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

                                if (val.total !== undefined) {
                                    var $td = $("<td style=\"position: relative;\"></td>");
                                    var $progress = $("<div class=\"progress\" />");

                                    percentage = val.used / val.total;
                                    $progress.css("width", (percentage * 100) + "%");

                                    if(percentage < 0.5) {
                                        $progress.addClass("good");
                                    } else if(percentage < 0.75) {
                                        $progress.addClass("ehh")
                                    } else {
                                        $progress.addClass("bad");
                                    }

                                    $td.append("<div class=\"stat\">" + val.used + "/" + val.total + " MB</div>");
                                    $td.append($progress);
                                    $row.append($td);
                                } else {
                                    $row.append("<td>" + val + "</td>");
                                }
                            }
                        }
                        $table.append($row);
                    });
                    $section.addClass(status ? "good" : "unknown");
                    $section.append($table);

                    // Fix subsection borders
                    $("td[colspan=2]").parent().css("border-bottom", "none");
                    // Add sliders
                    $section.children("aside").click(function() {
                        $(this).siblings("table").toggle();
                    });
                    updateLogo();
                }

                function updateLogo() {
                    var text = $("#logo").html();
                    if  (text.indexOf("<span>") == -1) {
                        var parts = $("#logo").html().split(" ");
                        parts[parts.length - 1] = "<span>" + parts[parts.length - 1] + "</span>";
                        $("#logo").html(parts.join(" "));
                    }

                    if  ($("#status_grid").children("section.good").length == $("#status_grid").children("section").length) {
                        $("#logo").addClass("good");
                        $("#logo").removeClass("unknown");
                    } else {
                        $("#logo").addClass("unknown");
                        $("#logo").removeClass("good");
                    }
                }

                function secondsToHms(seconds) {
                    seconds = Number(seconds);
                    var w = Math.floor(seconds / 604800);
                    var d = Math.floor(seconds % 604800 / 86400);
                    var h = Math.floor(seconds % 86400 / 3600);
                    var m = Math.floor(seconds % 3600 / 60);
                    var s = Math.floor(seconds % 3600 % 60);
                    return (w > 0 ? w + "w, " : "") + (d > 0 ? d + "d, " : "") + (h > 0 ? (h < 10 ? "0" : "") + h + ":" : "") + (m < 10 ? "0" : "") + m + ":" + (s < 10 ? "0" : "") + s;
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
                <!-- Various server statuses will be displayed here -->
            </section>
        </main>
    </body>
</html>
