<?php
namespace Authentication\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class AuthenticationTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function auth(Authentication $credentials)
    {
    
        $rowset = $this->tableGateway->select(['username' => $credentials->username , 'password' => $credentials->password]);
        
        $row = $rowset->current();
        if (! $row) {
            // throw new RuntimeException(sprintf(
            //     'Could not find row with identifier',
            // ));
            return;
        }

        return $row;
    }

    public function register(Authentication $user)
    {
        $user_data = [
            'username' => $user->username,
            'email'  => $user->email,
            'password' => $user->password
        ];

        $this->tableGateway->insert($user_data);
    }

}