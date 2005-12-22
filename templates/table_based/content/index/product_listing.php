<?php
/*
  $Id: index.php 199 2005-09-22 17:56:13 +0200 (Do, 22 Sep 2005) hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License
*/
?>

<?php echo tep_image(DIR_WS_IMAGES . $osC_Template->getPageImage(), $osC_Template->getPageTitle(), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, 'class="pageIcon"'); ?>

<h1><?php echo $osC_Template->getPageTitle(); ?></h1>

<?php
// optional Product List Filter
  if (PRODUCT_LIST_FILTER > 0) {
    if (isset($_GET['manufacturers']) && tep_not_null($_GET['manufacturers'])) {
      $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$_SESSION['languages_id'] . "' and p.manufacturers_id = '" . (int)$_GET['manufacturers'] . "' order by cd.categories_name";
    } else {
      $filterlist_sql = "select distinct m.manufacturers_id as id, m.manufacturers_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name";
    }

    $Qfilterlist = $osC_Database->query($filterlist_sql);
    $Qfilterlist->execute();

    if ($Qfilterlist->numberOfRows() > 1) {
      echo '<p>' . tep_draw_form('filter', FILENAME_DEFAULT, 'get') . TEXT_SHOW . '&nbsp;';
      if (isset($_GET['manufacturers']) && tep_not_null($_GET['manufacturers'])) {
        echo osc_draw_hidden_field('manufacturers', $_GET['manufacturers']);
        $options = array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES));
      } else {
        echo osc_draw_hidden_field('cPath', $cPath);
        $options = array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS));
      }
      echo osc_draw_hidden_field('sort', $_GET['sort']);

      while ($Qfilterlist->next()) {
        $options[] = array('id' => $Qfilterlist->valueInt('id'), 'text' => $Qfilterlist->value('name'));
      }
      echo osc_draw_pull_down_menu('filter', $options, (isset($_GET['filter']) ? $_GET['filter'] : ''), 'onchange="this.form.submit()"');
      echo '</form></p>' . "\n";
    }
  }

  $Qlisting = $osC_Products->execute();
  require('includes/modules/product_listing.php');
?>
