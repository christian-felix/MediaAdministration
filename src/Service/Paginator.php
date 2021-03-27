<?php


namespace src\Service;

/**
 * Class Paginator
 * @package config
 */
class Paginator implements \Iterator
{
    protected $itemPerPage = 3;
    protected $result = [];
    protected $pagesNumber = 0;
    protected $currentPage = 0;

    public function __construct()
    {
        $this->currentPage = 0;
    }

    /**
     * @param array $result
     */
    public function setResult(array $result)
    {
        $itemNumber = 0;
        $pageNumber = 0;
        $this->pagesNumber = ceil(count($result) / $this->itemPerPage);

        foreach ($result as $item) {

            $itemNumber++;
            $this->result[$pageNumber][] = $item;

            if ((int)fmod($itemNumber, $this->itemPerPage) === 0) {
                $pageNumber++;
            }
        }
    }

    public function current()
    {
      return $this->result[$this->currentPage];
    }

    public function prev()
    {
        if ($this->currentPage > 0) {
            $this->currentPage--;
        }
    }

    public function next()
    {
        if ($this->currentPage < $this->pagesNumber) {
            ++$this->currentPage;
        }
    }

    public function key()
    {
        return $this->currentPage;
    }

    public function valid(): bool
    {
        return isset($this->result[$this->currentPage]);
    }

    public function rewind()
    {
        $this->currentPage = 0;
    }
}