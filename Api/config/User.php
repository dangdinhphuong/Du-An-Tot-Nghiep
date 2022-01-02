<?php
return [
    'gender' => [
        'male' => 1,
        'female' => 0
    ],
    'action' => [
        'activated' => 1, //khích hoạt
        'disable' => 0 // vô hiệu hóa
    ],
    'userType' => [
        'Staff' => 1, //nhân viên
        'student' => 0 // học sinh
    ],
    'user_Type' => [
          1 => 'Nhân viên', //nhân viên
          0 => 'Khách thuê' // học sinh
    ],
    'action_status' => [
        1 => "Đang hoạt động", //khích hoạt
        0 => "Ngưng hoạt động" // vô hiệu hóa
  ],
  'status' => [
       
        1=> "Chờ xử lý", //nhân viên      
        2 => "Đang xử lý", //nhân viên
        3 => "Đã xử lý", //nhân viên
        4 => "Hết hạn xử lý", //nhân viên
           
    ],
    'range' => [
        [
            'range_id' => 1,
            'range_announcements' => "Tất cả"
        ],
        [
            'range_id' => 2,
            'range_announcements' => "Nhân viên"
        ],
        [
            'range_id' => 3,
            'range_announcements' => "Sinh viên"
        ]
    ],
    'StudentInter' => [
        'requestType' => [
            [
                'requestType_id' => 0,
                'requestType_name' => "Rỗng"
            ],
            [
                'requestType_id' => 1,
                'requestType_name' => "Báo hỏng"
            ],
            [
                'requestType_id' => 2,
                'requestType_name' => "Thanh lý hợp đồng"
            ],
            [
                'requestType_id' => 3,
                'requestType_name' => "Khác"
            ]
        ]

    ],
    'userType2' => [
        [
            'user_id' => "",
            'user_name' => "Tất cả"
        ],
        [
            'user_id' => 0,
            'user_name' => "Sinh viên"
        ],
        [
            'user_id' => 1,
            'user_name' => "Nhân viên"
        ]
    ],
    'action2' => [
        [
            'action_id' => "",
            'action_name' => "Tất cả"
        ],
        [
            'action_id' => 0,
            'action_name' => "Tài khoản hiệu hóa"
        ],
        [
            'action_id' => 1,
            'action_name' => "Tài khoản kích hoạt"
        ]
    ],
    'student_object' => [
        1 => "Học sinh",
        2 => "Sinh viên" ,
        3 => "Thạc sĩ ",
        4 => "Nghiên cứu sinh",
        5 => "Khác" ,
  ],
];
