<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require('Model/Connection.php');

class Date
{

    public $months = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');
    public $days = array('1', '2', '3', '4', '5', '6', '7');
    public $monthWeeks = array('1', '2', '3', '4');

    function getAll($year)
    {
        $weeks = [];

        for ($i = 0; $i < 52; $i++) {
            $week_start = date('d-m-Y', strtotime($year . 'W' . str_pad($i + 1, 2, 0, STR_PAD_LEFT)));
            $week_end = date('d-m-Y', strtotime($week_start . ' +6 days'));
            $weeks[$i] = $week_end;
        }

        $columns = 4;
        $weeksCount = count($weeks);
        $rows = ceil($weeksCount / $columns);

        ?>
        <form action="" method="post">
            <table>
                <?php for ($row = 0; $row < $rows; $row++) { ?>
                    <tbody>
                        <tr>
                            <?php for ($col = 0; $col < $columns; $col++) { ?>
                                <?php $index = $row * $columns + $col; ?>
                                <?php if ($index < $weeksCount) { ?>
                                    <td> 
                                        <label for="week_<?php echo $index; ?>">
                                            <?php echo $weeks[$index]; ?>
                                        </label>
                                        <select name="week">
                                            <?php try {
                                                // Connexion à MongoDB
                                                $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
                                                // filtre
                                                $filter = [];
                                                $option = [];
                                                $read = new MongoDB\Driver\Query($filter, $option);
                                                $all_users = $manager->executeQuery('Planning.users', $read);
                                                ?>
                                                <?php
                                                foreach ($all_users as $user) { ?>

                                                    <option value="<?php echo $user->_id?>">
                                                        <?php echo nl2br($user->prenom); ?>
                                                    </option>
                                                <?php } ?>
                                                <?php
                                            } catch (MongoDB\Driver\ConnectionException $e) {
                                                echo $e->getMessage();
                                            } ?>

                                        </select>
                                    </td>
                                <?php } else { ?>
                                    <td></td>
                                    <td></td>
                                <?php } ?>
                            <?php } ?>
                        </tr>
                    <?php } ?>
            </table>
            <input type="submit" name="submit" value="Submit">
        </form>
        <?php
      
    }
}
?>