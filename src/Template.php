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
		$tags = TagQuery::create()->leftJoin('Tag.PostTag')->with('PostTag')
						->leftJoin('PostTag.Post')->with('Post')
						->orderBy('Post.CreatedAt', Criteria::DESC)->find();

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

		$dataPost = new Data('post', null, "div", 'title');
		$dataPost->deny()->allow('title');
		$dataPost->getDisplayComponent()->setHtmlTag('a')->setHtmlCallback(function($title) {
			$title = str_replace(' ', '-', $title);
			return "href='index.php?title=$title'";
		});

		$dataPostTag = new Data('post_tag');
		$dataPostTag->link('post_id', $dataPost);

		$dataTag = new Data('tag');
		$dataTag->addExtraColumn('extra')->link('extra', new Group($dataPostTag), 'tag_id');
		$groupTag = new Group($dataTag);
		$groupTag->setAfterOpeningTag('<h3>Posts</h3>');
		$groupTag->setInput($tags);

		$sidebar = new Wrapper($groupTag, 'div', 'sidebar col-md-3');
		$sidebar->addExtraComponent($actions, false);
		$sidebar->addExtraComponent(new Component('h3', 'brand', null, 'Blog'), false);
		$this->addExtraComponent($sidebar, false);

		$content = new Wrapper(null, 'div', 'content col-md-9 col-md-offset-3');

		if (count($_POST) !== 0) {
			Box::get()->server()->batch($_POST);
		}

		$this->content = $content;
		$this->sidebar = $sidebar;
		parent::__construct($content, 'WebDreamt Blog');
	}

}
