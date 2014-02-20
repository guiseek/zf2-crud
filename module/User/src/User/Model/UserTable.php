<?php

namespace User\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Crypt\Password\Bcrypt;

class UserTable extends AbstractTableGateway
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select(function (Select $select) {
            $select->order('id DESC');
        });
        return $resultSet;
    }

    public function getUser($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveUser(User $user)
    {
        $bcrypt = new Bcrypt();
        $password = $bcrypt->create($user->password);
        
        $data = array(
            'username' => $user->username,
            'password'  => $password,
            'nome' => $user->nome,
            'email' => $user->email,
        );

        $id = (int) $user->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Id não existe.');
            }
        }
    }

    public function deleteUser($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

    // Usado para paginação
    public function getSql()
    {
        return $this->tableGateway->getSql();
    }
    public function getSelect($where = null, $order = null)
    {
        $select = new Select($this->tableGateway->getTable());
        if ($where) {
            $select->where($where);
        }
        if ($order) {
            $select->order($order);
        }
        return $select;
    }
}