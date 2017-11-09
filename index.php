<?php 
    setlocale(LC_ALL, 'Dutch_Netherlands', 'Dutch', 'nl_NL', 'nl', 'nl_NL.ISO8859-1', 'nl_NL.UTF-8', 'nld_nld', 'nld', 'nld_NLD', 'NL_nl');
    include "config.php";

    function percentageOf( $number, $everything, $decimals = 0 ){
        if($everything == 0){
            return "";
        }else{
            return round( $number / $everything * 100, $decimals );
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title></title>
        <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $(function () {
                $('form').on('submit', function (e) {
                    $.ajax({
                        type: 'post',
                        url: 'fetch_data.php',
                        data: $(this).serialize(),
                        success: function (data) {
                            var json = $.parseJSON(data);
                            $("#"+json.id+"_pos").css("width", json.pos+"%");
                            $("#"+json.id+"_neg").css("width", json.neg+"%");
                            //alert(json.message);
                        }
                    });
                    e.preventDefault();
                });
            });
        </script>
    </head>
    <body>
	    <!-- Fixed navbar -->
	    <nav class="navbar navbar-default navbar-fixed-top">
	      <div class="container">
	        <div class="navbar-header">
            <!--
	          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	            <span class="sr-only">Toggle navigation</span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
            -->
	          <a class="navbar-brand" href="./">Nespresso Flavours</a>
	        </div>
            <!--
	        <div id="navbar" class="collapse navbar-collapse">
	          <ul class="nav navbar-nav">
	            <li class="active"><a href="./">Home</a></li>
	          </ul>
	        </div>
            -->
	      </div>
	    </nav>

        <div class="container">
            <div class="row no-gutter">
                <div class="col-md-6 col-md-offset-3 col-xs-12 padding-0">
					<div class="panel-group" id="accordion">
                    <?php
                        // select first 3 posts
                        $query = "select * from flavours order by id";
                        $result = $db->query($query);

                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $id = $row['id'];
                            $flavour = $row['flavour'];
                            $descr_title = $row['descr_title'];
                            $descr_text = $row['descr_text'];
                            $price = $row['price'];
                            $intensity = $row['intensity'];
                            $link = $row['link'];
                            $image = $row['image'];
                            $pos = $row['pos'];
                            $neg = $row['neg'];
                            $total = $pos + $neg;
                    ?>

                        <div class="post panel panel-default margin-0" id="post_<?php echo $id; ?>">
                            <div class="panel-heading row" data-toggle="collapse" data-parent="#accordion" data-target="#collapse_<?php echo $id; ?>">
                                <div class="col-xs-12">
                                    <span class="pull-left"><img src="<?php echo $image; ?>" class="img-responsive" style="max-height: 50px;"></span>
                                    <span class="pull-left" style="padding-top: 7px; padding-left: 15px;">
                                        <strong><?php echo $flavour; ?></strong>
                                        <br>
                                        <i><?php echo $descr_title ?></i>
                                    <span>
                                </div>
                            </div>

                            <div id="collapse_<?php echo $id; ?>" class="panel-collapse collapse">
                                <div class="panel-body">

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-8">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="progress">
                                                        <div id="<?php echo $id; ?>_pos" class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo percentageOf($pos,$total); ?>%">
                                                            Like
                                                        </div>
                                                        <div id="<?php echo $id; ?>_neg"class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php echo percentageOf($neg,$total); ?>%">
                                                            Hate
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <form>
                                                        <button type="submit" class="btn btn-default btn-lg pull-left">
                                                            <span class="glyphicon glyphicon-thumbs-up" style="color:green" aria-hidden="true"></span>
                                                            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                                                            <input type="hidden" name="vote" id="vote" value="pos">
                                                        </button>
                                                    </form>

                                                    <form>
                                                        <button type="submit" class="btn btn-default btn-lg pull-right">
                                                            <span class="glyphicon glyphicon-thumbs-down" style="color:red" aria-hidden="true"></span>
                                                            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                                                            <input type="hidden" name = "vote" id="vote" value="neg">
                                                        </button>
                                                </form>
                                                </div>
                                            </div>
                                            <div class="margin-5" style="padding-top:10px;">
                                                <p class="margin-0"><?php echo $descr_text; ?></p>
                                            </div>
                                            <div class="margin-5">
                                                <p class="margin-0"><strong>Price</strong></p>
                                                <p class="margin-0"><?php echo $price; ?></p>
                                            </div>
                                            <div class="margin-5">
                                                <p class="margin-0"><strong>Link</strong></p>
                                                <p class="margin-0"><a href="<?php echo $link; ?>" target="_blank">Product pagina</a></p>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 hidden-xs">
                                            <div class="margin-5">
                                                <p class="margin-0"><img src="<?php echo $image; ?>" class="img-responsive"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                    </div> <!-- Panel group -->
                </div> <!-- Col -->
            </div> <!-- Row -->
        </div>

    </body>
</html>
