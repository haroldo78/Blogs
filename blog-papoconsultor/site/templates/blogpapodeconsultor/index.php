<?php defined( '_JEXEC' ) or die; 



// variables

$app = JFactory::getApplication();
$doc = JFactory::getDocument(); 
$menu = $app->getMenu();
$active = $app->getMenu()->getActive();
$params = $app->getParams();
$pageclass = $params->get('pageclass_sfx');
$tpath = $this->baseurl.'/templates/'.$this->template;
// generator tag
$this->setGenerator(null);
// template css
$doc->addStyleSheet($tpath.'/css/template.css.php');

?><!doctype html>
<html lang="<?php echo $this->language; ?>">
<head>

  <jdoc:include type="head" />

  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
  <link rel="apple-touch-icon-precomposed" href="<?php echo $tpath; ?>/images/apple-touch-icon-57x57-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $tpath; ?>/images/apple-touch-icon-72x72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $tpath; ?>/images/apple-touch-icon-114x114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $tpath; ?>/images/apple-touch-icon-144x144-precomposed.png">
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,300italic,600italic,700' rel='stylesheet' type='text/css'>

<script> 
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){ 
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o), 
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m) 
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga'); 

  ga('create', 'UA-67712533-1', 'auto'); 
  ga('send', 'pageview'); 

</script>﻿
</head>
<body class="<?php echo (($menu->getActive() == $menu->getDefault()) ? ('front') : ('site')).' '.$active->alias.' '.$pageclass; ?>">
  <?php if ($menu->getActive() == $menu->getDefault()) : ?>
  <section class="banner">
      <jdoc:include type="modules" name="banner-top" />
      <div class="centraliza">
         <jdoc:include type="modules" name="logo-banner-top" />
        <jdoc:include type="modules" name="menu-redesocial" />
        <div class="formulario">
         
        </div>
     </div>
  </section>
   <?php endif; ?>
   <div class="refTop"></div>
   <header id="topo" class="topo ">
    <div class="centraliza">
        <jdoc:include type="modules" name="logo" />
        <jdoc:include type="modules" name="newsletter" />
        <span class="menu-mobile open"></span>
        <span class="menu-mobile  close" style="display:none"></span>
        <div id="menu" class="full fixed" style="display: none;">
          <nav class="mobile">
            <jdoc:include type="modules" name="menu-principal" style="xhtml" /> 
          </nav>
        </div>
      </div>
   </header>
      <section class="conteudo">
        <jdoc:include type="component" />
         <jdoc:include type="modules" name="redes-social" />
          <jdoc:include type="modules" name="relacionado" style="xhtml" /> 
      </section>
   <footer class="rodape">
        <div class="centraliza">
        <jdoc:include type="modules" name="footer" />
        <jdoc:include type="modules" name="menu-principal" style="xhtml" /> 
        <jdoc:include type="modules" name="facebook" style="xhtml" /> 
      </div>
    </footer> 
   <div class="centraliza">
        <jdoc:include type="modules" name="copyright"  />
   </div>

  <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  <script type="text/javascript" src="<?php echo $tpath; ?>/js/scripts.js"></script>
</body>
</html>