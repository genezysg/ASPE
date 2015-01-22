<?php

namespace Acme\BlogBundle\Handler;

use Acme\BlogBundle\Model\PageInterface;
use Acme\BlogBundle\Entity\Page;

interface PageHandlerInterface
{
    /**
     * Get a Page given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return PageInterface
     */
    public function get($id);

    /**
     * Get a list of Pages.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0);

    /**
     * Post Page, creates a new Page.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return PageInterface
     */
    
    public function post(array $parameters);
    
    /**
     * Delete a Page.
     *
     * @param PageInterface $page
     *
     */
    public function delete(Page $page);
    /**
     * Edit a Page.
     *
     * @api
     *
     * @param PageInterface   $page
     * @param array           $parameters
     *
     * @return PageInterface
     */
    public function put(PageInterface $page, array $parameters);

    /**
     * Partially update a Page.
     *
     * @api
     *
     * @param PageInterface   $page
     * @param array           $parameters
     *
     * @return PageInterface
     */
    public function patch(PageInterface $page, array $parameters);
}