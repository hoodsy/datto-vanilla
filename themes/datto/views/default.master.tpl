<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {asset name="Head"}
    <!-- Fontastic -->
    <link href="https://fontastic.s3.amazonaws.com/q3XbZVqTMGwGBcap5rtsJC/icons.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">

  </head>
  <body id="{$BodyID}" class="{$BodyClass}">
    <!--[if lt IE 8]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <header class="navbar" role="banner">
      <nav class="navbar-nav lt-flex-center" role="navigation">
        <div class="navbar-brand">
          <a class="navbar-logo fontify-datto" href="{link path="home"}">{logo}</a>
        </div>
        <div class="navbar-meModule">
          {module name="MeModule"}
        </div>
      </nav>

      {if InSection("CategoryList")}
        {pocket name="landing-subheader"}
      {/if}
      {if !InSection("CategoryList")}

      {/if}
    </header>

    <main class="container site-container" role="main">

      <nav class="trail">
        {breadcrumbs}
      </nav>

      <div class="row">

        <section class="site-content">
          {asset name="Content"}
        </section>

        <aside class="site-sidebar">
          {asset name="Panel"}
        </aside>

      </div>

    </main>

    <footer class="site-footer" role="contentinfo">
      <div class="container">
        <p class="pull-left">{t c="Copyright"} &copy; {$smarty.now|date:"%Y"} <a href="{link path="home"}">{logo}</a>. {t c="All rights reserved"}.</p>
      </div>
    </footer>

    {asset name="Foot"}
    {event name="AfterBody"}
  </body>
</html>
