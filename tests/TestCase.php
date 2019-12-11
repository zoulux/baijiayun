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

    public function testVerify()
    {
        $data = [
            'video_id' => '30054501',
            'status' => '20',
            'total_size' => '774452',
            'file_md5' => '946aeebfc09dbe7c2bbc7deb9a27e796',
            'room_id' => '19121181366617',
            'qid' => '201912111342051110803564',
            'timestamp' => '1576042925',
            'sign' => 'f1d368e0ff2dbad2fcef6912965ae42a',
        ];

        $bjcloud = new  \Jake\Baijiayun\BJCloud([
            'partner_idd' => '###',
            'partner_key' => '###'
        ]);

        $res = $bjcloud->verify($data);

        $this->assertIsArray($res);
    }

}
