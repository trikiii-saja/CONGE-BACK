<?php
namespace App\Filters\V1;
use Illuminate\Http\Request;
use App\Filters\ApiFilter;

class UsersFilter extends ApiFilter {
      protected $safeParms = [
            'id' => ['eq'],
      ];
      protected $columnMap = [
             //variant
      ];
      protected $operatorMap =[
            'eq' => '=',
            'lt' => '<',
            'gt' => '>',
            'lte' => '<=',
            'gte' => '>='  //in,like,not
      ];
}