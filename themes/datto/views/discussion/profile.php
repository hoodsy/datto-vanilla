<?php if (!defined('APPLICATION')) exit();

echo '<div class="DataListWrap">';
echo '<h4 class="H discussions-label">'.t('Comments').'</h4>';
echo '<ul class="DataList SearchResults Discussions">';

if (sizeof($this->data('Comments'))) {
    echo $this->fetchView('profilecomments', 'Discussion', 'Vanilla');
    echo $this->Pager->toString('more');
} else {
    echo '<li class="Item Empty">'.t('This user has not commented yet.').'</li>';
}
echo '</ul>';
echo '</div>';
