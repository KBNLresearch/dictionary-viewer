<!DOCTYPE html>
<html xmlns:xlink="http://www.w3.org/1999/xlink">
    <head>
        <meta content="text/html" charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>Dictionary viewer</title>
        <link rel="stylesheet" type="text/css" href="stylesheets/style.css" />
        <link rel="stylesheet" type="text/css" href="stylesheets/jquery.fs.selecter.css" />
    </head>
    <body>
        
        <div id="wrapper">

            <div id="logo">
                <h1>Dictionary viewer</h1>
            </div>

            <div id="search">
                <form id="form">

                    <input type="text" name="dict" id="dict" autocomplete="off" spellcheck="false" placeholder="Enter dictionary terms separated by spaces">
                    <!--                    
                    <select name="score" id="score">
                        <option value="0">all scores</option>
                        <option value="1">min 1</option>
                        <option value="2">min 2</option>
                        <option value="3">min 3</option>
                    </select>
                    -->
                    <select name="words" id="words">
                        <option value="1">min 1 word</option>
                        <option value="2">min 2 words</option>
                        <option value="3">min 3 words</option>
                        <option value="4">min 4 words</option>
                        <option value="5">min 5 words</option>
                        <option value="10">min 10 words</option>
                        <option value="15">min 15 words</option>
                    </select>

                    <input type="submit" class="button" id="submit" value="Search">

                </form>
            </div>

            <div id="message"></div>

            <div id="chart"></div>

            <div id="settings">

                <h2>Dictionary settings</h2>

                <table id="list" border="0">
                    <tr class="table-header">
                        <th class="color"></th>
                        <th class="dict">Dictionary terms</th>
                        <th class="count"># hits</th>
                        <th class="min_words"># words</th>
                        <th class="download"></th>
                        <th class="delete"></th>
                    </tr>
                </table>

                <h2>Chart settings</h2>

                <div id="frequency-buttons">
                    <input type="button" id="relative" class="freq button selected" value="Relative frequency">
                    <input type="button" id="absolute" class="freq button" value="Absolute frequency">
                    <div class="clear"></div>
                </div>
            </div>

            <div id="footer"></div>

        </div>
        <!-- JavaScript libraries --> 
        <script type="text/javascript" charset="UTF-8" src="scripts/libraries/jquery.min.js"></script>
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> -->
        <script type="text/javascript" charset="UTF-8" src="scripts/libraries/jquery.fs.selecter.js"></script>
        <script type="text/javascript" charset="UTF-8" src="scripts/libraries/d3.min.js"></script>
        <script type="text/javascript" charset="UTF-8" src="scripts/libraries/d3.tip.js"></script>
        <!-- Dict Viewer classes -->
        <script type="text/javascript" charset="UTF-8" src="scripts/Dict.js"></script>
        <script type="text/javascript" charset="UTF-8" src="scripts/DictSet.js"></script>
        <script type="text/javascript" charset="UTF-8" src="scripts/DictChart.js"></script>
        <!-- Dict Viewer app -->
        <script type="text/javascript" charset="UTF-8" src="scripts/app.js"></script>
    </body>
</html>

