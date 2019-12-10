<?php

namespace Jake\Baijiayun\Tests;


use PHPUnit\Framework\TestCase as BaseTestCase;


class TestCase extends BaseTestCase
{

    public function testRoomCreate()
    {
        $bjcloud = new  \Jake\Baijiayun\BJCloud([
            'partnerId' => '####',
            'partnerKey' => '####'
        ]);
        $res = $bjcloud->roomCreate('live', time() + 60 * 60, time() + 60 * 60 * 2);

        $this->assertArrayHasKey('room_id', $res);
        $this->assertArrayHasKey('student_code', $res);
        $this->assertArrayHasKey('admin_code', $res);
        $this->assertArrayHasKey('teacher_code', $res);
    }

}
