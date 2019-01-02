
<?php defined( '_JEXEC' ) or die; 

include_once JPATH_THEMES.'/'.$this->template.'/logic.php';

?><!doctype html>

<html lang="<?php echo $this->language; ?>">

<head>
  <jdoc:include type="head" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
  <link rel="apple-touch-icon-precomposed" href="<?php echo $tpath; ?>/images/apple-touch-icon-57x57-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $tpath; ?>/images/apple-touch-icon-72x72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $tpath; ?>/images/apple-touch-icon-114x114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $tpath; ?>/images/apple-touch-icon-144x144-precomposed.png">
  <link href='http://fonts.googleapis.com/css?family=Signika:400,300,600,700' rel='stylesheet' type='text/css'>
  <link href='http://fonts.googleapis.com/css?family=Amatic+SC:400,700' rel='stylesheet' type='text/css'>
  <link href='http://fonts.googleapis.com/css?family=Amatic+SC:400,700' rel='stylesheet' type='text/css'>
  <style type="text/css">
    html::-webkit-scrollbar {width:8px; height:auto;background:#F4F2E9;}
    html::-webkit-scrollbar-corner { background: #F4F2E9;}
    html::-webkit-scrollbar-button:vertical {height:0px;display:block;}
    html::-webkit-scrollbar-button:horizontal {width:1px;display:block;}
    html::-webkit-scrollbar-thumb:vertical {background:#664545;border: 0px;border-right:none;-webkit-border-radius: 3px;}
    html::-webkit-scrollbar-thumb:horizontal {background:#664545;border: 0px ;border-bottom;}
    html::-webkit-scrollbar-thumb  {background:#664545;}
    html::-webkit-scrollbar-thumb:hover {background:#664545;border: 0;}
    html::-webkit-scrollbar-track-piece {background: none;}
    html::-webkit-scrollbar:vertical {border-left: 0;}
    html::-webkit-scrollbar:horizontal {border-top: 0;}   
  </style>
</head>

<body class="<?php echo (($menu->getActive() == $menu->getDefault()) ? ('front') : ('site')).' '.$active->alias.' '.$pageclass; ?>">
  <header class="topo geral" id="top">
    <div class="container">
      <div class="logo">
        <h1><a href="index.php">Puro Sabor</a></h1>
      </div>
      <div class="redes">
        <jdoc:include type="modules" name="redes" style="xhtml"/>
      </div>
      <nav class="menu"><jdoc:include type="modules" name="menu"/></nav>
      <nav class="mobile"><span class="menu-mobile"></span><jdoc:include type="modules" name="menu" /></nav>      

    </div>
  </header>
  <section class="conteudo container">
    <section class="container-d"> 
      <jdoc:include type="modules" name="widgets" style="xhtml"/>
    </section>
    <?php if ($menu->getActive() == $menu->getDefault()) : ?>
    <section class="container-e"> 
      <jdoc:include type="component" />
    </section>
    <?php else : ?>
    <article class="container-e"> 
      <jdoc:include type="component" />
      <jdoc:include type="modules" name="relacionados" style="xhtml"/>
    </article>
    <?php endif; ?>

 </section>
<div class="go-up default"><a href="#top" rel="" class="anchorLink">topo</a></div>
<footer class="rodape">
<section class="container">
  <jdoc:include type="modules" name="footer" />
</section>
</footer> 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<script type="text/javascript" language="javascript" src="http://www.orbitando.com.br/clientes/wstc/js/jquery.anchor.js" ></script>
<script src="<?php echo $tpath; ?>/js/topo.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo $tpath; ?>/js/main.min.js" ></script>


<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v2.3";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


</body>



</html>
