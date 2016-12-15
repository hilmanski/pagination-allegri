<?php
namespace PaginationAllegri;
use \PDO;
/*
 * Pagination Class - Pagination Allegri
 * by Hilman Ramadhan >> @hilmanrdn 14-12-2016
 */
class Pagination
{
    private $db, $table, $total_records, $limit = 5, $col, $val;

    /*
     * Construct: set table, connection and total records
     * @param host, databasename, user, pass, table
     */
    public function __construct($host, $dbname, $user, $pass, $table)
    {
        $this->db = new PDO("mysql:host=$host;dbname=$dbname", "$user", "$pass");
        $this->table = $table;
        $this->set_total_records();
    }

    /*
     * Set Total Records
     * called by construct
     */
    private function set_total_records()
    {
        $query = "SELECT id FROM $this->table";
        if ($this->is_search())
            $query = "SELECT id FROM $this->table WHERE $this->col LIKE '%$this->val%'";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $this->total_records = $stmt->rowCount();
    }

    /*
     * Get All Data
     * @return Data Object
     */
    public function get_data()
    {
        $start = 0;
        if ($this->current_page() > 1)
            $start = ($this->current_page() * $this->limit) - $this->limit;

        $query = "SELECT * FROM $this->table LIMIT $start, $this->limit";
        if ($this->is_search())
            $query = "SELECT * FROM $this->table WHERE $this->col LIKE '%$this->val%' LIMIT $start, $this->limit";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /*
     * Check if there any search query
     * set val and set col
     */
    public function is_search()
    {
        if(isset($_GET['search'])) {
            $this->val = $_GET['search'];
            $this->col = $_GET['col'];
            return true;
        }

        return false;
    }

    /*
     * Set parameter search
     * called on usage, by button-links
     */
    public function get_search_param()
    {
        if ($this->is_search())
            return '&search=' . $this->val .'&col='. $this->col;

        return '';
    }

    /*
     * Get Current page
     * @return number
     */
    public function current_page()
    {
        return isset($_GET['page']) ? (int)$_GET['page'] : 1;
    }

    /*
     * Get Total Pages
     * @return number
     */
    public function get_numbers()
    {
        return ceil($this->total_records / $this->limit);
    }

    /*
     * Get previous page
     * @return number
     */
    public function prev_page()
    {
        return ($this->current_page() > 1) ? $this->current_page()-1 : 1;
    }

    /*
     * Get next page
     * @return number
     */
    public function next_page()
    {
        return ($this->current_page() < $this->get_numbers()) ? $this->current_page() + 1 : $this->get_numbers();
    }

    /*
     * Check Active Page
     * used for css
     * @return string
     */
    public function is_active_page($num)
    {
        return ($num == $this->current_page()) ? 'active' : '';
    }

    /*
     * Show page number, only for +- 2
     * @param pages number
     */
    public function is_showable($num)
    {
        if ( ($this->get_numbers() < 4 || $this->current_page() == $num)
            || (($this->current_page()-2) <= $num && ($this->current_page()+2) >= $num ))
            return true;
    }

}
