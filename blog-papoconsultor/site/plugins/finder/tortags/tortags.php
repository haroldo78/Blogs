<?php
/**
* TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.0
* @package TorTags
* @author Tormix Team
* @Copyright Copyright (C) Tormix, www.tormix.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('JPATH_BASE') or die;

jimport('joomla.application.component.helper');

// Load the base adapter.
require_once JPATH_ADMINISTRATOR . '/components/com_finder/helpers/indexer/adapter.php';

/**
 * Finder adapter for com_content.
 *
 * @package     Joomla.Plugin
 * @subpackage  Finder.Content
 * @since       2.5
 */
class plgFinderTortags extends FinderIndexerAdapter
{
	/**
	 * The plugin identifier.
	 *
	 * @var    string
	 * @since  2.5
	 */
	protected $context = 'Tortags';

	/**
	 * The extension name.
	 *
	 * @var    string
	 * @since  2.5
	 */
	protected $extension = 'com_tortags';

	/**
	 * The sublayout to use when rendering the results.
	 *
	 * @var    string
	 * @since  2.5
	 */
	protected $layout = 'tag';

	/**
	 * The type of content that the adapter indexes.
	 *
	 * @var    string
	 * @since  2.5
	 */
	protected $type_title = 'Tag';

	/**
	 * The table name.
	 *
	 * @var    string
	 * @since  2.5
	 */
	protected $table = '#__tortags_tags';

	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An array that holds the plugin configuration
	 *
	 * @since   2.5
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	protected function getURL($id, $extension, $view)
	{
		//return 'index.php?option=' . $extension . '&view=' . $view . '&tag=' . $id;
		return 'index.php?option=com_search&view=search&areas[0]=tortags&searchword=' . $id;
	}
	
	protected function index(FinderIndexerResult $item, $format = 'html')
	{
		// Check if the extension is enabled
		if (JComponentHelper::isEnabled($this->extension) == false)
		{
			return;
		}
		
		$registry = new JRegistry;
		$registry->loadString($item->params);
		$item->params = $registry;
		$registry = new JRegistry;
		$registry->loadString($item->metadata);
		$item->metadata = $registry;

		$app    = JApplication::getInstance('site');
		$router = &$app->getRouter();
		
		$item->url = $this->getURL($item->title, $this->extension, $this->layout);
		$uri = $router->build($item->url );
		$glink = str_replace('/administrator','',$uri->toString());
		
		$item->route = $glink;
		$item->path = FinderIndexerHelper::getContentPath($item->route);

		$item->addInstruction(FinderIndexer::META_CONTEXT, 'title');

		$item->addTaxonomy('Type', 'Tag');

		FinderIndexerHelper::getContentExtras($item);

		// Index the item.
		$version = new JVersion;
		$ver = $version->getShortVersion();
		if (substr($ver,0,strpos($ver,'.'))==3)
		{
			$this->indexer->index($item);
		}else FinderIndexer::index($item);
	}

	protected function setup()
	{
		
		return true;
	}
	
	protected function getListQuery($sql = null)
	{
		$db = JFactory::getDbo();
		$sql = is_a($sql, 'JDatabaseQuery') ? $sql : $db->getQuery(true);
		
		$sql->select('a.id, a.title AS title, "" AS alias,1 AS state, 1 AS access');
		$sql->select("CONCAT('Tag: ',a.title) AS summary");
		// Handle the alias CASE WHEN portion of the query
		$case_when_item_alias = ' CASE WHEN ';
		$case_when_item_alias .= $sql->charLength('a.title');
		$case_when_item_alias .= ' THEN ';
		$a_id = $sql->castAsChar('a.id');
		$case_when_item_alias .= $sql->concatenate(array($a_id, 'a.title'), ':');
		$case_when_item_alias .= ' ELSE ';
		$case_when_item_alias .= $a_id.' END as slug';
		$sql->select($case_when_item_alias);
		$sql->from('#__tortags_tags AS a');
		$sql->where('`a`.`published`=1');
		return $sql;
	}
}
