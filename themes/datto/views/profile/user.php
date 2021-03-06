<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::session();
?>
<h4 class="H discussions-label">About</h4>
<div class="User" itemscope itemtype="http://schema.org/Person">
    <?php
    // $Photo = userPhoto($Row, array('LinkClass' => 'Img'));
    // if ($Photo) {
    //     echo $Photo;
    // }
    // Define the current profile picture
    $Picture = '';
    if ($this->User->Photo != '') {
      if (IsUrl($this->User->Photo)) {
        $Picture = img($this->User->Photo, array('class' => 'ProfilePhoto'));
      } else {
        $Picture = img(Gdn_Upload::url(changeBasename($this->User->Photo, 'p%s')), array('class' => 'ProfilePhoto'));
      }
      echo $Picture;
    } else {
      $Photo = userPhoto($Row, array('LinkClass' => 'Img'));
      if ($Photo) {
        echo $Photo;
      }
    }
    ?>
    <div class="profile-info">
    <h4 class="H profile-username"><?php

        echo htmlspecialchars($this->User->Name);

        // echo '<span class="Gloss">';
        // Gdn_Theme::BulletRow();
        // if ($this->User->Title) {
        //     echo Gdn_Theme::BulletItem('Title');
        //     echo ' '.Bullet().' '.Wrap(htmlspecialchars($this->User->Title), 'span', array('class' => 'User-Title'));
        // }

        // $this->fireEvent('UsernameMeta');
        // echo '</span>';
        ?></h4>
    <h4 class="profile-email"><?php
      echo htmlspecialchars($this->User->Email);
    ?></h4>
    </div>

    <?php
    if ($this->User->Admin == 2) {
        echo '<div class="DismissMessage InfoMessage">', t('This is a system account and does not represent a real person.'), '</div>';
    }

    if ($this->User->About != '') {
        echo '<div id="Status" itemprop="description">'.Wrap(Gdn_Format::Display($this->User->About));
        if ($this->User->About != '' && ($Session->UserID == $this->User->UserID || $Session->checkPermission('Garden.Users.Edit')))
            echo ' - '.anchor(t('clear'), '/profile/clear/'.$this->User->UserID.'/'.$Session->TransientKey(), 'Hijack');

        echo '</div>';
    }

    echo Gdn_Theme::Module('UserBanModule', array('UserID' => $this->User->UserID));

    $this->fireEvent('BeforeUserInfo');
    echo Gdn_Theme::Module('UserInfoModule');
    $this->fireEvent('AfterUserInfo');
    ?>
</div>
