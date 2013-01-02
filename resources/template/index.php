<?php

 use ProjectTest\Service\View; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <!--[if lt IE 9]><script src=http://html5shiv.googlecode.com/svn/trunk/html5.js></script><![endif]-->
        <link rel="stylesheet" href="style/site.css">
        <link rel="stylesheet" href="style/theme/spacelab/bootstrap.min.css">
        <!--[if lt IE 9]><link rel=stylesheet href=css/ie.css><![endif]-->
        <script src="http://code.jquery.com/jquery-1.8.2.js"></script>
        <script src="script/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="/">LeandroLeite.info</a>
                    <div class="nav-collapse collapse boxSocial">
                        <ul class="nav pull-right notPadding backgroundColorGray">
                            <li><a href="http://www.linkedin.com/in/lleitep3" target="social">
                                    <img class="small" src="/image/linkedin.png"/>
                                </a>
                            </li>
                            <li>
                                <a href="http://twitter.com/lleitep3" target="social">
                                    <img class="small" src="/image/twitter.png"/>
                                </a>
                            </li>
                            <li>
                                <a href="https://plus.google.com/u/0/115421391210030552899" target="social">
                                    <img class="small" src="/image/googleplus.png"/>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav menuInstitucional">
                            <li><a href="/">Home</a></li>
                            <li><a href="/artigos">Artigos</a></li>
                            <li><a href="/projetos">Projetos</a></li>
                            <li><a href="/leandroleite">Um pouco do Man</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container-fluid body">
            <div class="row-fluid content">
                <div class="span9">
                    <?php View::add($page); ?>
                </div>
                <div class="span3">
                    <div class="well sidebar-nav">
                        <ul class="nav nav-list">
                            <li class="nav-header">Artigos</li>
                            <li class="active"><a href="#">Link</a></li>
                            <li><a href="#">Link</a></li>
                            <li><a href="#">Link</a></li>
                            <li><a href="#">Link</a></li>
                            <li class="nav-header">Indico a Leitura</li>
                            <li><a href="#">Link</a></li>
                            <li><a href="#">Link</a></li>
                            <li><a href="#">Link</a></li>
                        </ul>
                    </div><!--/.well -->
                </div><!--/span-->
            </div><!--/row-->

            <footer>
                <hr>
                <p>&copy; Hey Man - lleitep3 since 2012</p>
            </footer>

        </div>
        <script type="text/javascript">
            var uri = window.location.pathname;
            $('.menuInstitucional a[href="' + uri + '"]')
                    .parent('li').addClass('active');
        </script>
    </body>
</hrml>