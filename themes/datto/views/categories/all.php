<?php if (!defined('APPLICATION')) exit();

// Potentially need to use https for production?
define('BASE_URL', "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");

// RSS Data Formatter
function formatRssData ($data)
{
  // Split data on url string
  $recentPosts = explode(BASE_URL, $data);

  // Retrieve and format 3 most recent discussions
  $recentPosts = array($recentPosts[3], $recentPosts[4], $recentPosts[5]);
  for ($i=0; $i < 3; $i++) {
    $recentPosts[$i] = cleanDiscussionMetaData($recentPosts[$i]);
    $recentPosts[$i] = buildDiscussionTemplate($recentPosts[$i]);
  }

  return $recentPosts;
}

function formatTitle ($discussionUrl)
{
  $discussionUrl = str_replace('</link>', '', $discussionUrl);
  $discussionTitle = explode('/', $discussionUrl);
  $discussionTitle = rtrim($discussionTitle[2], "<");
  $discussionTitle = str_replace('-', ' ', $discussionTitle);
  return array(
    'text' => $discussionTitle,
    'url' => $discussionUrl
  );
}

function formatCreated ($discussionItems)
{
  $discussionCreated = $discussionItems[2];
  for ($i=3; $i < 6; $i++) {
    $discussionCreated .= ' '.$discussionItems[$i];
  }
  return humanTiming(
    strtotime($discussionCreated)
  );
}

function formatAuthor ($discussionAuthor)
{
  $discussionAuthor = str_replace('<dc:creator>', '', $discussionAuthor);
  $discussionAuthor = str_replace('</dc:creator>', '', $discussionAuthor);
  return $discussionAuthor;
}

function cleanDiscussionMetaData ($discussionItems)
{

  // Remove Category name and split string on spaces
  $discussionItems = preg_replace('/(<category>(.*)<\/category>)/', '', $discussionItems);
  $discussionItems = preg_split('/\s+/', $discussionItems);

  // Format / retrieve desirable discussion metadata
  $discussionTitle = formatTitle($discussionItems[0]);
  $discussionCreated = formatCreated($discussionItems);
  $discussionAuthor = formatAuthor($discussionItems[7]);

  // print_r($discussionItems);

  return array(
    'title' => $discussionTitle,
    'created' => $discussionCreated,
    'author' => $discussionAuthor
  );
}

function buildDiscussionTemplate ($discussionMetaData)
{
  // If there is no RSS data, hide item
  if (   $discussionMetaData['author'] == ''
      || $discussionMetaData['created'] == ''
      || $discussionMetaData['title']['url'] == '')
  {
    return '<div class="discussion-meta hide"></div>';
  }

  // print_r('\n=======');
  // print_r($discussionMetaData['author']);

  $titleTemplate = (
    '<a '
      .'class="discussion-meta-title"'
      .'href="'.BASE_URL.$discussionMetaData['title']['url'].'">'
      .$discussionMetaData['title']['text']
    .'</a>'
  );
  $createdTemplate = (
    '<p '
      .'class="discussion-meta-created">'
      .$discussionMetaData['created']
      .' ago by'
    .'</p>'
  );
  $authorTemplate = (
    '<a '
      .'class="discussion-meta-author"'
      .'href="'.BASE_URL.'index.php?p=/profile/'.$discussionMetaData['author'].'">'
      .$discussionMetaData['author']
    .'</a>'
  );

  return (
    '<div class="discussion-meta">'
      .$titleTemplate
      .$createdTemplate
      .$authorTemplate
    .'</div>'
  );
}

// Calculate 'x minutes ago' from current time
function humanTiming ($time)
{
    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }
}
// End RSS Formatter


if (!function_exists('GetOptions'))
    include $this->fetchViewLocation('helper_functions', 'categories');

echo '<h1 class="H HomepageTitle">'.$this->data('Title').'</h1>';
if ($Description = $this->Description()) {
    echo wrap($Description, 'div', array('class' => 'P PageDescription'));
}
$this->fireEvent('AfterPageTitle');

$CatList = '';
$DoHeadings = c('Vanilla.Categories.DoHeadings');
$MaxDisplayDepth = c('Vanilla.Categories.MaxDisplayDepth') + $this->data('Category.Depth', 0);
$ChildCategories = '';
$this->EventArguments['NumRows'] = count($this->data('Categories'));

//if (c('Vanilla.Categories.ShowTabs')) {
////   $ViewLocation = Gdn::controller()->fetchViewLocation('helper_functions', 'Discussions', 'vanilla');
////   include_once $ViewLocation;
////   WriteFilterTabs($this);
//   echo Gdn_Theme::Module('DiscussionFilterModule');
//}

echo '<ul class="DataList CategoryList'.($DoHeadings ? ' CategoryListWithHeadings' : '').'">';
$Alt = FALSE;
foreach ($this->data('Categories') as $CategoryRow) {
    global $CategoriesURL;
    $Category = (object)$CategoryRow;

    $this->EventArguments['CatList'] = &$CatList;
    $this->EventArguments['ChildCategories'] = &$ChildCategories;
    $this->EventArguments['Category'] = &$Category;
    $this->fireEvent('BeforeCategoryItem');
    $CssClass = CssClass($CategoryRow);

    $CategoryID = val('CategoryID', $Category);

    if ($Category->CategoryID > 0) {
        // If we are below the max depth, and there are some child categories
        // in the $ChildCategories variable, do the replacement.
        if ($Category->Depth < $MaxDisplayDepth && $ChildCategories != '') {
            $CatList = str_replace('{ChildCategories}', '<span class="ChildCategories">'.Wrap(t('Child Categories:'), 'b').' '.$ChildCategories.'</span>', $CatList);
            $ChildCategories = '';
        }

        if ($Category->Depth >= $MaxDisplayDepth && $MaxDisplayDepth > 0) {
            if ($ChildCategories != '')
                $ChildCategories .= ', ';
            $ChildCategories .= anchor(Gdn_Format::text($Category->Name), CategoryUrl($Category));
        } else if ($Category->DisplayAs === 'Heading') {
            $CatList .= '<li id="Category_'.$CategoryID.'" class="CategoryHeading '.$CssClass.'">
               <div class="ItemContent Category">'.GetOptions($Category, $this).Gdn_Format::text($Category->Name).'</div>
            </li>';
            $Alt = FALSE;
        } else {
            $LastComment = UserBuilder($Category, 'Last');
            $AltCss = $Alt ? ' Alt' : '';
            $Alt = !$Alt;

            // Get RSS Feed (XML) data for Category
            $rssData = file_get_contents(BASE_URL."index.php?p=/categories/".$Category->UrlCode."/feed.rss");
            $rssData = formatRssData($rssData);

            $CatList .= '<li id="Category_'.$CategoryID.'" class="'.$CssClass.'">
               <div class="ItemContent Category">'
                .GetOptions($Category, $this)
                .CategoryPhoto($Category)
                .'<div class="TitleWrap">'
                .anchor(Gdn_Format::text($Category->Name), CategoryUrl($Category), 'Title')
                .'</div>
                  <div class="CategoryDescription">'
                .$Category->Description
                .'</div>'

                // Display RSS Data
                .$rssData[0]
                .$rssData[1]
                .$rssData[2]

                  .'<div class="Meta">
                     <span class="MItem RSS">'.anchor(img('applications/dashboard/design/images/rss.gif', array('alt' => T('RSS Feed'))), '/categories/'.$Category->UrlCode.'/feed.rss', '', array('title' => T('RSS Feed'))).'</span>
                     <span class="MItem DiscussionCount fontify-discussions-icon"><span>'.sprintf(Plural(number_format($Category->CountAllDiscussions), '%s', '%s'), $Category->CountDiscussions).'</span></span>
                     <span class="MItem CommentCount">'.sprintf(Plural(number_format($Category->CountAllComments), '%s comment', '%s comments'), $Category->CountComments).'</span>';

            if ($Category->LastTitle != '') {
                $CatList .= '<span class="MItem LastDiscussionTitle">'.sprintf(
                        t('Most recent: %1$s by %2$s'),
                        anchor(Gdn_Format::text(sliceString($Category->LastTitle, 40)), $Category->LastUrl),
                        userAnchor($LastComment)
                    ).'</span>'
                    .'<span class="MItem LastCommentDate">'.Gdn_Format::date($Category->LastDateInserted).'</span>';
            }
            // If this category is one level above the max display depth, and it
            // has children, add a replacement string for them.
            if ($MaxDisplayDepth > 0 && $Category->Depth == $MaxDisplayDepth - 1 && $Category->TreeRight - $Category->TreeLeft > 1)
                $CatList .= '{ChildCategories}';

            $CatList .= '</div>
               </div>
            </li>';
        }
    }
}
// If there are any remaining child categories that have been collected, do
// the replacement one last time.
if ($ChildCategories != '')
    $CatList = str_replace('{ChildCategories}', '<span class="ChildCategories">'.Wrap(t('Child Categories:'), 'b').' '.$ChildCategories.'</span>', $CatList);

echo $CatList;
?>
</ul>
