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

    {asset name="NewDiscussionButton"}
    <header class="navbar" role="banner">
      <nav class="navbar-nav lt-flex-center" role="navigation">
        <div class="navbar-brand">
          <a class="navbar-logo fontify-datto" href="{link path="home"}">{logo}</a>
          <p class="navbar-product">Community</p>
        </div>
        <div class="navbar-meModule">
          {module name="MeModule"}
        </div>
      </nav>
    </header>

    <!-- Headers -->
    <!-- CategoryList -->
    {if $Categories}
      <div class="categories-header ">
        <h1>Welcome to the Partner Community</h1>
        <h3>What's on your mind?</h3>
        <div class="search-container">
          <i class="fontify-search-icon"></i>
          {searchbox placeholder="Search the Partner Community"}
        </div>
      </div>
    {/if}
    <!-- DiscussionsList -->
    {if $Discussions && !$Profile}
      <div class="discussions-header">
        <div class="discussions-header-container">
          <div class="discussions-header-text">
            <h3>{$Title}</h3>
            <p>{$Category.Description}</p>
          </div>
          <div class="discussions-search-container">
            <i class="fontify-search-icon"></i>
            {searchbox placeholder="Search the Community"}
          </div>
        </div>
      </div>
    {/if}
    <!-- Search Results -->
    {if $Title == 'Search'}
      <div class="discussions-header">
        <div class="discussions-header-container">
          <div class="discussions-header-text">
            <h3>Search Results for &ldquo;{$SearchTerm}&rdquo;</h3>
            {breadcrumbs}
          </div>
          <div class="discussions-search-container">
            <i class="fontify-search-icon"></i>
            {searchbox placeholder="Search the Community"}
          </div>
        </div>
      </div>
    {/if}
    <!-- Discussion -->
    {if $Discussion}
      <div class="discussions-header">
        <div class="discussions-header-container">
          <div class="discussions-header-text">
            <h3>{$Category.Name}</h3>
            {breadcrumbs}
          </div>
          <div class="discussions-search-container">
            <i class="fontify-search-icon"></i>
            {searchbox placeholder="Search the Community"}
          </div>
        </div>
      </div>
    {/if}
    <!-- New Discussion -->
    {if $Title == 'New Discussion'}
      <div class="discussions-header">
        <div class="discussions-header-container">
          <div class="discussions-header-text">
            <h3>What's on your mind?</h3>
            {breadcrumbs}
          </div>
          <div class="discussions-search-container">
            <i class="fontify-search-icon"></i>
            {searchbox placeholder="Search the Community"}
          </div>
        </div>
      </div>
    {/if}
    <!-- New Conversation -->
    {if $Title == 'New Message'}
      <div class="discussions-header">
        <div class="discussions-header-container">
          <div class="discussions-header-text">
            <h3>What's on your mind?</h3>
            {breadcrumbs}
          </div>
          <div class="discussions-search-container">
            <i class="fontify-search-icon"></i>
            {searchbox placeholder="Search the Community"}
          </div>
        </div>
      </div>
    {/if}
    <!-- Profile -->
    {if $Profile}
      <div class="discussions-header">
        <div class="discussions-header-container">
          <div class="discussions-header-text">
            <h3>Manage Profile</h3>
            {breadcrumbs}
          </div>
          <div class="discussions-search-container">
            <i class="fontify-search-icon"></i>
            {searchbox placeholder="Search the Community"}
          </div>
        </div>
      </div>
    {/if}
    <!-- Profile -->
    {if $Conversation || $Conversations}
      <div class="discussions-header">
        <div class="discussions-header-container">
          <div class="discussions-header-text">
            <h3>Messages</h3>
            {breadcrumbs}
          </div>
          <div class="discussions-search-container">
            <i class="fontify-search-icon"></i>
            {searchbox placeholder="Search the Community"}
          </div>
        </div>
      </div>
    {/if}

    <!-- Body -->
    <main class="container site-container" role="main">
    <!-- Content -->
      <div class="site-row">
        <section class="site-content">
          {asset name="Content"}
        </section>

        <!-- Default Sidebar -->
        {if !$Profile
         && !$Conversations
         && !$Conversation
         && !$Discussion
        }
        <aside class="site-sidebar">
          {module name="NewDiscussionModule"}
          {module name="PromotedContentModule"
            Selector="Category"
            ContentType="discussions"
            Selection="Announcements"
            Limit="5"
          }
          <a class="Button sidebar-coc"
            href="">
            Code of Conduct
          </a>
        </aside>
        {/if}
        <!-- Discussion Sidebar -->
        {if $Discussion}
        <aside class="site-sidebar">
          <a class="Button discussions-new-comment"
            href="#Form_Comment">
            New Comment
          </a>
          {module name="NewDiscussionModule"}
          {module name="PromotedContentModule"
            Selector="Category"
            ContentType="discussions"
            Selection="Announcements"
            Limit="5"
          }
          <a class="Button sidebar-coc"
            href="">
            Code of Conduct
          </a>
        </aside>
        {/if}
        <!-- Profile Sidebar -->
        {if $Profile
         && $Title != 'Edit Profile'
         && $Title != 'Change My Password'
         && $Title != 'Change Picture'
        }
        <aside class="site-sidebar">
          {module name="NewDiscussionModule"}
          {module name="ProfileFilterModule"}
        </aside>
        {/if}
        <!-- Edit Profile Sidebar -->
        {if $Title == 'Edit Profile'
         || $Title == 'Change My Password'
         || $Title == 'Change Picture'
        }
        <aside class="site-sidebar">
          {module name="NewDiscussionModule"}
          <ul class="BoxProfileFilter">
            {if $Title == 'Edit Profile'}
              <li class="Active"><a href="/profile/edit">Edit Profile</a></li>
              <li><a href="/profile/password">Change Password</a></li>
              <li><a href="/profile/picture">Change Picture</a></li>
            {/if}
            {if $Title == 'Change My Password'}
              <li><a href="/profile/edit">Edit Profile</a></li>
              <li class="Active"><a href="/profile/password">Change Password</a></li>
              <li><a href="/profile/picture">Change Picture</a></li>
            {/if}
            {if $Title == 'Change Picture'}
              <li><a href="/profile/edit">Edit Profile</a></li>
              <li><a href="/profile/password">Change Password</a></li>
              <li class="Active"><a href="/profile/picture">Change Picture</a></li>
            {/if}
          </ul>
        </aside>
        {/if}
        <!-- Messages Sidebar -->
        {if $Conversation
         || $Conversations}
        <aside class="site-sidebar">
          {module name="NewConversationModule"}
        </aside>
        {/if}

      </div>

    </main>



    <!-- <footer class="site-footer" role="contentinfo">
      <div class="container">
        <p class="pull-left">{t c="Copyright"} &copy; {$smarty.now|date:"%Y"} <a href="{link path="home"}">{logo}</a>. {t c="All rights reserved"}.</p>
      </div>
    </footer> -->

    {asset name="Foot"}
    {event name="AfterBody"}
  </body>
</html>
