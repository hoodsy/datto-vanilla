<?php if (!defined('APPLICATION')) exit();

echo '<h4 class="H discussions-label">'.t('Notifications').'</h4>';
echo '<div class="DataListWrap">';

if (count($this->data('Activities'))) {
    echo '<ul class="DataList Activities Notifications">';
    include($this->fetchViewLocation('activities', 'activity', 'dashboard'));
    echo '</ul>';
    echo PagerModule::write(array('CurrentRecords' => count($this->data('Activities'))));
} else {
    ?>
    <div class="Empty"><?php echo t('You do not have any notifications yet.'); ?></div>
<?php
}
echo '</div>';
