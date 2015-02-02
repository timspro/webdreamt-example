<?php
use Propel\Runtime\ActiveQuery\Criteria;
use WebDreamt\Component;
use WebDreamt\Component\Custom;
use WebDreamt\Component\Icon;
use WebDreamt\Component\Wrapper;
use WebDreamt\Component\Wrapper\Data;
use WebDreamt\Component\Wrapper\Data\Form;
use WebDreamt\Component\Wrapper\Group;
use WebDreamt\Component\Wrapper\Modal;
use WebDreamt\Component\Wrapper\Page;
use WebDreamt\Store;

class Template extends Page {

	/**
	 * The sidebar. Ignores input from render.
	 * @var Component
	 */
	public $sidebar;
	/**
	 * The content. Gets input from render.
	 * @var Wrapper
	 */
	public $content;

	function __construct() {
		$root = Box::get()->root();

		//Make a store to help organize things.
		$store = new Store();

		$store->set('data', function() {
			//Get data from database.
			$data = TagQuery::create()->leftJoin('Tag.PostTag')->with('PostTag')
							->leftJoin('PostTag.Post')->with('Post')
							->orderBy('Post.CreatedAt', Criteria::DESC)->find();
			return $data;
		});

		//Start making components.
		//Set up the general layout of the sidebar.
		$store->set('sidebar', function() use ($store, $root) {
			$sidebar = new Wrapper($store->get('tags'), 'div', 'sidebar col-xs-3');
			$sidebar->addExtraComponent($store->get('untagged_posts'));
			$sidebar->addExtraComponent($store->get('actions'), false);
			$logo = new Component('img', 'logo col-xs-6', "src='$root/dist/img/bismuth.png'", '');
			$sidebar->addExtraComponent($logo, false);
			$sidebar->addExtraComponent(new Component('h3', 'brand', null, 'A Blog'), false);
			return $sidebar;
		});

		//Set up the component for links to admin areas.
		$store->set('actions', function() use ($root) {
			$actions = new Custom(function() use ($root) {
				ob_start();
				?>
				<div class="actions">
					<div><a href="<?= $root ?>/admin/adminer.php">Adminer</a></div>
					<div><a href="<?= $root ?>/admin/manager.php">Manager</a></div>
					<div><a href="<?= $root ?>/admin/authorization.php">Authorization</a></div>
					<div><a href="<?= $root ?>/admin/create.php">Create</a></div>
					<div><a href="<?= $root ?>/index.php">Main</a></div>
					<div><a href="<?= $root ?>/logout.php">Logout</a></div>
				</div>
				<?php
				return ob_get_clean();
			}, true);
			$actions->setGroups('admin');
			return $actions;
		});

		//Set up the list of tags.
		$store->set('tags', function() use ($root, $store) {
			$tag = new Data('tag');
			$tag->setDataClass('tag');
			$tag->addExtraColumn('extra')->link('extra', new Group($store->get('post_tag')), 'tag_id');

			$icon = new Icon(Icon::TYPE_EDIT);
			$icon->setGroups('admin');
			$tag->addIcon($icon, "$root/modal.php", true);

			$icon = new Icon(Icon::TYPE_DELETE);
			$icon->setGroups('admin');
			$tag->addIcon($icon, '');

			$tags = new Group($tag);
			$tags->setAfterOpeningTag('<h3>Sweet Content</h3>');
			$tags->setInput($store->get('data'));
			return $tags;
		});

		//Set up the post tag component that links to posts.
		$store->set('post_tag', function() use ($store) {
			$postTag = new Data('post_tag');
			$postTag->link('post_id', $store->get('post'));
			$icon = new Icon(Icon::TYPE_DELETE);
			$icon->setGroups('admin');
			$postTag->addIcon($icon, '');
			return $postTag;
		});

		//Set up the post component that links via page titles to blog posts.
		$store->set('post', function() use ($root) {
			$post = new Data('post', null, "div", 'title');
			$post->deny()->allow('title');
			$post->getDisplayComponent()->setHtmlTag('a')->setHtmlCallback(function($title) use ($root) {
				$title = str_replace(' ', '-', $title);
				return "href='$root/index.php?title=$title'";
			});

			return $post;
		});

		$store->set('untagged_posts', function() use($store) {
			$data = PostQuery::create()->usePostTagQuery(null, 'left join')->filterByTagId(null)
							->endUse()->orderBy('Post.CreatedAt', Criteria::DESC)->find();
			$group = new Group($store->get('post'));
			$group->setInput($data);
			$group->addExtraComponent(new Custom(function($input) {
				if (count($input) !== 0) {
					return 'Unknown';
				}
				return '';
			}, true, 'div', 'tag-name'), false);
			return $group;
		});

		$sidebar = $store->get('sidebar');
		$content = new Wrapper(null, 'div', 'content col-xs-9 col-xs-offset-3');
		$this->addExtraComponent($sidebar, false);

		$this->content = $content;
		$this->sidebar = $sidebar;

		parent::__construct($content, 'A Blog');
	}

}
