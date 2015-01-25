<?php
use Propel\Runtime\ActiveQuery\Criteria;
use WebDreamt\Component;
use WebDreamt\Component\Custom;
use WebDreamt\Component\Wrapper;
use WebDreamt\Component\Wrapper\Data;
use WebDreamt\Component\Wrapper\Group;
use WebDreamt\Component\Wrapper\Page;

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
		$posts = PostQuery::create()->orderByCreatedAt(Criteria::DESC)->find();

		$actions = new Custom(function() {
			if (Box::get()->sentry()->getUser()) {
				ob_start();
				?>
				<div class="actions">
					<div><a href="admin/manager.php">Manager</a></div>
					<div><a href="admin/authorization.php">Authorization</a></div>
					<div><a href="index.php">Posts</a></div>
					<div><a href="create.php">Create</a></div>
					<div><a href="logout.php">Logout</a></div>
				</div>
				<?php
				return ob_get_clean();
			}
		}, true);

		$data = new Data('post', null, "div", 'title');
		$data->deny()->allow('title');
		$data->getDisplayComponent()->setHtmlTag('a')->setHtmlCallback(function($title) {
			$title = str_replace(' ', '-', $title);
			return "href='index.php?title=$title'";
		});
		$group = new Group($data);
		$group->setInput($posts);
		$group->setAfterOpeningTag('<h3>Posts</h3>');
		$sidebar = new Wrapper($group, 'div', 'sidebar col-md-3');
		$sidebar->addExtraComponent($actions, false);
		$sidebar->addExtraComponent(new Component('h3', 'brand', null, 'Blog'), false);
		$this->addExtraComponent($sidebar, false);

		$content = new Wrapper(null, 'div', 'content col-md-9 col-md-offset-3');

		$this->content = $content;
		$this->sidebar = $sidebar;
		parent::__construct($content, 'WebDreamt Blog');
	}

}
