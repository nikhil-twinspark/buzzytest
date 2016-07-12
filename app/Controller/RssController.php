<?php

/**
 *	CakePHP Controller to get rss feed from buzzydoc blog and  parse XML file.
 */
class RssController extends AppController
{

    public $name = "Rss";
    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array(
        'RssFeed'
    );
    /**
     * Array variable to store rss value.
     * @var type 
     */
    public $rss_item = array();

    protected $_feed = "http://blog.buzzydoc.com/feed/";
    /**
     * For Staff site we use the staffLayout layout
     * @var type 
     */
    public $layout = 'staffLayout';
    /**
     * Getting the list of rss feeds from blog and store it our system.
     */
    public function index()
    {
        $this->layout = "";
        $rss = simplexml_load_file($this->_feed);
        $rss_split = array();
        foreach ($rss->channel->item as $item) {
            $title = $item->title; // Title
            $link = $item->link; // Url Link
            $description = $item->description; // Description
            $rss_data[] = array(
                'link' => $link,
                'title' => $title,
                'description' => $description
            );
        }
        
        if (! empty($rss_data)) {
            $this->RssFeed->query('TRUNCATE TABLE rss_feeds;');
            $this->RssFeed->saveAll($rss_data);
        }
        exit();
    }
    /**
     * Getting the list of rss feeds from our system.
     */
    public function getRssFeeds(){
        $this->layout = "";
        $data = $this->RssFeed->find('all',array( 'fields'=>array('link','title') ));
        if($data){
            $data = array_column($data, 'RssFeed');
        }
        return $data;
    }
}
?>