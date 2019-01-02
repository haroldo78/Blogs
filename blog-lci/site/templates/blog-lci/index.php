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
  <link href='https://fonts.googleapis.com/css?family=Ubuntu:400,300,500,700' rel='stylesheet' type='text/css'>

</head>
  
<body class="<?php echo (($menu->getActive() == $menu->getDefault()) ? ('front') : ('site')).' '.$active->alias.' '.$pageclass; ?>">
  <header id="topo" class="topo ">
    <div class="centraliza">
      <h1><a href="index.php">Blog LCI</a></h1>
       <jdoc:include type="modules" name="redes-social" style="xhtml" /> 
        <jdoc:include type="modules" name="newsletter" />
        <span class="menu-mobile open"></span>
        <span class="menu-mobile  close" style="display:none"></span>
        <div id="menu" class="full fixed" style="display: none;">
           <jdoc:include type="modules" name="buscar" /> 
          <nav class="mobile">
            <jdoc:include type="modules" name="menu-principal" style="xhtml" /> 
          </nav>
           <jdoc:include type="modules" name="banner-comercial" /> 
        </div>
      </div>
   </header>
   <section class="conteudo">
        <jdoc:include type="component" />
          <jdoc:include type="modules" name="compartilhar" style="xhtml" /> 
          <jdoc:include type="modules" name="postrelacionados" style="xhtml" /> 
    </section>
   <footer class="rodape">
        <div class="centraliza">
        <jdoc:include type="modules" name="footer" style="xhtml" />
        <jdoc:include type="modules" name="menu-principal" style="xhtml" /> 
        <div class="face-rodape">
          <div id="fb-root"></div>
            <script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v2.0";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
          </script>
          <div class="fb-like-box" data-href="https://www.facebook.com/LCImoveisOficial?fref=ts" data-show-faces="true" data-header="true" data-stream="false" data-show-border="false"></div>
        </div>
      </div>
    </footer> 
    <div class="rodape-copyright">
       <div class="centraliza">
            <jdoc:include type="modules" name="copyright"  />
       </div>
    </div>
      <script src="<?php echo $tpath; ?>/js/scripts.js"></script>

</body>

</html>