<!DOCTYPE html>

<html lang="en">
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>BART</title>
    
    <link rel="stylesheet" href="css/style.css">

    <link rel="icon" type="image/png" href="img/favicon.png">

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
    <script type="text/javascript" src="js/scripts.js"></script>

  </head>
  
  <body>
    <div class="container-fluid">
		<div class="row">
		    <div class="col-md-5">
		        <h1 class="logo"><a href="/">BART</a></h1>
                <h2 class="subtitle">Bay Area Rapid Transit</h2>		
		    </div>

            <div class="col-md-6">
                <form id="route_form">
                    <select class="form-control dropdown" id="route_select">

                        <?php foreach ($routes as $route): ?>
                            <option value="<?= $route['number'] ?>"><?= $route['number'] ?> - <?= $route['name'] ?></option>
                        <?php endforeach ?>

                    </select>
                 </form>
             </div>
             <div class="col-md-1">
				 <a href="help.html" target="_blank" data-toggle="tooltip" title="Instructions"><span class="glyphicon glyphicon-size glyphicon-question-sign"></span></a>				 
			 </div>
          </div>
    </div>

    <div id="map-canvas"></div><!-- #map-canvas -->

    <footer class="footer">
      <p>This is project2 for <a href="http://cs75.tv">Harvard Summer School Course: Computer Science E-75</a>. This website 
      uses <a href="http://www.bart.gov/schedules/developers/api">BART API</a>
       and <a href="https://developers.google.com/maps/documentation/javascript/">Google Map Javascript API</a>. 
       Source codes can be found <a href="https://github.com/yuan9778/BART">here</a>. &copy; SQY - 2015</p>
    </footer>
  </body>
</html>
