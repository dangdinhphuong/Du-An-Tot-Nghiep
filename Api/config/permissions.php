<?php
return [
    'permission' => [
        [
            "name" => 'Danh sách dashboard',
            "desc" => 'Biểu đồ thống kê',
            'permission_childen' => [
                [
                    "name" => 'Index dashboard',
                    "key_code" => 'dash-board-list',
                ],
                [
                    "name" => 'Dashboard tổng số giường',
                    "key_code" => 'dash-board-total-bed',
                ],
                [
                    "name" => 'Dashboard người dùng theo tháng',
                    "key_code" => 'dash-board-month-user',
                ],
            ],
        ],
        [
            "name" => 'Hợp đồng',
            "desc" => 'Quản lí cho thuê',
            'permission_childen' => [
                [
                    "name" => 'danh sách hợp đồng',
                    "key_code" => 'hop-dong-list',
                ],
                [
                    "name" => 'Hợp đồng - danh sách Dự án',
                    "key_code" => 'project-list',
                ],
                [
                    "name" => 'Hợp đồng - danh sách tòa nhà',
                    "key_code" => 'building-list',
                ],
                [
                    "name" => 'thêm hợp đồng',
                    "key_code" => 'hop-dong-add',
                ],
                [
                    "name" => 'chi tiết hợp đồng',
                    "key_code" => 'hop-dong-detail',
                ],
                [
                    "name" => 'sửa hợp đồng',
                    "key_code" => 'hop-dong-edit',
                ],
                [
                    "name" => 'xóa hợp đồng',
                    "key_code" => 'hop-dong-delete',
                ],
                [
                    "name" => 'in hợp đồng',
                    "key_code" => 'hop-dong-print',
                ],
                [
                    "name" => 'chuyển giường',
                    "key_code" => 'hop-dong-change-bed',
                ],
                [
                    "name" => 'thu cọc',
                    "key_code" => 'hop-dong-collect-deposit',
                ],
                [
                    "name" => 'lịch sử thu phí',
                    "key_code" => 'hop-dong-history-invoice-list',
                ],
                [
                    "name" => 'chi tiết lịch sử thu phí',
                    "key_code" => 'hop-dong-history-invoice-edit',
                ],
            ],
        ],
        [
            "name" => 'Lịch sử thanh lý HĐ',
            "desc" => 'Quản lí cho thuê',
            'permission_childen' => [
                [
                    "name" => 'danh sách lịch sử thanh lý HĐ',
                    "key_code" => 'history-contract-list',
                ],
                [
                    "name" => 'Lịch sử thanh lý HĐ - danh sách phòng',
                    "key_code" => 'project-list',
                ],
                [
                    "name" => 'chi tiết lịch sử thanh lý HĐ',
                    "key_code" => 'history-contract-edit',
                ],
            ],
        ],
        [
            "name" => 'Chốt điện nước',
            "desc" => 'Quản lí cho thuê',
            'permission_childen' => [
                [
                    "name" => 'danh sách ghi điện nước',
                    "key_code" => 'electricity-water-list',
                ],
                [
                    "name" => 'Chốt điện nước - danh sách Dự án',
                    "key_code" => 'project-list',
                ],
                [
                    "name" => 'Chốt điện nước - danh sách tòa nhà',
                    "key_code" => 'building-list',
                ],
                [
                    "name" => 'sửa số ghi điện nước',
                    "key_code" => 'electricity-water-add',
                ],
                [
                    "name" => 'chi tiết số ghi điện nước',
                    "key_code" => 'electricity-water-detail',
                ],

            ],
        ],
        [
            "name" => 'Thu phí',
            "desc" => 'Quản lí cho thuê',
            'permission_childen' => [
                [
                    "name" => 'danh sách thu phí theo hợp đồng',
                    "key_code" => 'invoice-contract-list',
                ],
                [
                    "name" => 'Thu phí - danh sách Dự án',
                    "key_code" => 'project-list',
                ],
                [
                    "name" => 'Thu phí - danh sách tòa nhà',
                    "key_code" => 'building-list',
                ],
                [
                    "name" => 'tạo hóa đơn thu phí theo hợp đồng',
                    "key_code" => 'invoice-contract-add',
                ],
                [
                    "name" => 'Chi tiết hóa đơn thu phí theo hợp đồng',
                    "key_code" => 'invoice-contract-edit',
                ],
                [
                    "name" => 'danh sách thu phí theo tháng',
                    "key_code" => 'invoice-month-list',
                ],
                [
                    "name" => 'tạo hóa đơn thu phí theo tháng',
                    "key_code" => 'invoice-month-add',
                ],
                [
                    "name" => 'Thanh toán hóa đơn thu phí',
                    "key_code" => 'invoice-pay',
                ],
                [
                    "name" => 'Chi tiết hóa đơn thu phí theo tháng',
                    "key_code" => 'invoice-month-edit',
                ],
            ],
        ],
        [
            "name" => 'Bảo chì sửa chữa',
            "desc" => 'Quản lí công việc',
            'permission_childen' => [
                [
                    "name" => 'danh sách sửa chữa',
                    "key_code" => 'repair-list',
                ],
                [
                    "name" => 'thêm sửa chữa',
                    "key_code" => 'repair-add',
                ],
                [
                    "name" => 'cập nhật sửa chữa',
                    "key_code" => 'repair-edit',
                ],
                [
                    "name" => 'cập nhật trạng thái sửa chữa',
                    "key_code" => 'repair-status-edit',
                ],
                [
                    "name" => 'xóa sửa chữa',
                    "key_code" => 'repair-delete',
                ],
            ],
        ],
        [
            "name" => 'Công việc thực hiện',
            "desc" => 'Quản lí công việc',
            'permission_childen' => [
                [
                    "name" => 'danh sach task',
                    "key_code" => 'task-list',
                ],
                [
                    "name" => 'thêm task',
                    "key_code" => 'task-add',
                ],
                [
                    "name" => 'sửa task',
                    "key_code" => 'task-edit',
                ],
                [
                    "name" => 'xóa task',
                    "key_code" => 'task-delete',
                ],
                [
                    "name" => 'chi tiết task',
                    "key_code" => 'task-detail',
                ],
                [
                    "name" => 'cập nhật task',
                    "key_code" => 'task-status-edit',
                ],

            ],
        ],
        [
            "name" => 'Khách thuê',
            "desc" => 'Quản lí khách thuê',
            'permission_childen' => [
                [
                    "name" => 'danh sách khách thuê',
                    "key_code" => 'tenant-list',
                ],
                [
                    "name" => 'sửa khách thuê',
                    "key_code" => 'tenant-edit',
                ],
                [
                    "name" => 'thêm khách thuê',
                    "key_code" => 'tenant-add',
                ]

            ],
        ],
        [
            "name" => ' Tương tác khách thuê',
            "desc" => 'Quản lí khách thuê',
            'permission_childen' => [
                [
                    "name" => 'danh sách tương tác',
                    "key_code" => 'Interactive-list',
                ],
                [
                    "name" => 'thêm tương tác',
                    "key_code" => 'Interactive-add',
                ],
                [
                    "name" => 'sửa tương tác',
                    "key_code" => 'Interactive-edit',
                ],
                [
                    "name" => 'xóa tương tác',
                    "key_code" => 'Interactive-delete',
                ],
                [
                    "name" => 'chi tiết tương tác',
                    "key_code" => 'Interactive-detail',
                ],
                [
                    "name" => 'in tương tác',
                    "key_code" => 'Interactive-print',
                ],

            ],
        ],
        [
            "name" => 'Thông báo',
            "desc" => 'Quản lí khách thuê',
            'permission_childen' => [
                [
                    "name" => 'danh sách thông báo',
                    "key_code" => 'Notify-list',
                ],
                [
                    "name" => 'thêm thông báo',
                    "key_code" => 'Notify-add',
                ],
                [
                    "name" => 'sửa thông báo',
                    "key_code" => 'Notify-edit',
                ],
                [
                    "name" => 'xóa thông báo',
                    "key_code" => 'Notify-delete',
                ],
                [
                    "name" => 'chi tiết thông báo',
                    "key_code" => 'Notify-detail',
                ],
                [
                    "name" => 'in thông báo',
                    "key_code" => 'Notify-print',
                ],

            ],
        ],
        [
            "name" => 'Lý do thu',
            "desc" => 'Quản lí thu',
            'permission_childen' => [
                [
                    "name" => 'danh sách lý do thu',
                    "key_code" => 'reasons-list',
                ],
                [
                    "name" => 'thêm lý do thu',
                    "key_code" => 'reasons-add',
                ],
                [
                    "name" => 'sửa lý do thu',
                    "key_code" => 'reasons-edit',
                ],
                [
                    "name" => 'xóa lý do thu',
                    "key_code" => 'reasons-delete',
                ],
                [
                    "name" => 'chi tiết lý do thu',
                    "key_code" => 'reasons-detail',
                ],
                [
                    "name" => 'in lý do thu',
                    "key_code" => 'reasons-print',
                ],

            ],
        ],
        [
            "name" => 'Danh sách phiếu thu',
            "desc" => 'Quản lí thu',
            'permission_childen' => [
                [
                    "name" => 'Danh sách phiếu thu',
                    "key_code" => 'receipts-list',
                ],
                [
                    "name" => 'Chi tiết phiếu thu',
                    "key_code" => 'receipts-edit',
                ],
            ],
        ],
        [
            "name" => 'Lập phiếu thu',
            "desc" => 'Quản lí thu',
            'permission_childen' => [
                [
                    "name" => 'Thêm phiếu thu',
                    "key_code" => 'Receipt-add',
                ],

            ],
        ],
        [
            "name" => 'Tài sản',
            "desc" => 'Danh mục tài sản',
            'permission_childen' => [
                [
                    "name" => 'danh sách tài sản',
                    "key_code" => 'asset-list',
                ],
                [
                    "name" => 'thêm tài sản',
                    "key_code" => 'asset-add',
                ],
                [
                    "name" => 'sửa tài sản',
                    "key_code" => 'asset-edit',
                ],
                [
                    "name" => 'xóa tài sản',
                    "key_code" => 'asset-delete',
                ],
                [
                    "name" => 'chi tiết tài sản',
                    "key_code" => 'asset-detail',
                ],
                [
                    "name" => 'in tài sản',
                    "key_code" => 'asset-print',
                ],

            ],
        ],
        [
            "name" => 'Nhà sản xuất',
            "desc" => 'Danh mục tài sản',
            'permission_childen' => [
                [
                    "name" => 'danh sách nhà sản xuất',
                    "key_code" => 'producer-list',
                ],
                [
                    "name" => 'thêm nhà sản xuất',
                    "key_code" => 'producer-add',
                ],
                [
                    "name" => 'sửa nhà sản xuất',
                    "key_code" => 'producer-edit',
                ],
                [
                    "name" => 'xóa nhà sản xuất',
                    "key_code" => 'producer-delete',
                ],
                [
                    "name" => 'chi tiết nhà sản xuất',
                    "key_code" => 'producer-detail',
                ],
                [
                    "name" => 'in nhà sản xuất',
                    "key_code" => 'producer-print',
                ],

            ],
        ],
        [
            "name" => 'Loại tài sản',
            "desc" => 'Danh mục tài sản',
            'permission_childen' => [
                [
                    "name" => 'danh sách loại tài sản',
                    "key_code" => 'type-asset-list',
                ],
                [
                    "name" => 'thêm loại tài sản',
                    "key_code" => 'type-asset-add',
                ],
                [
                    "name" => 'sửa loại tài sản',
                    "key_code" => 'type-asset-edit',
                ],
                [
                    "name" => 'xóa loại tài sản',
                    "key_code" => 'type-asset-delete',
                ],
                [
                    "name" => 'chi tiết loại tài sản',
                    "key_code" => 'type-asset-detail',
                ],
                [
                    "name" => 'in loại tài sản',
                    "key_code" => 'type-asset-print',
                ],

            ],
        ],
        [
            "name" => 'Đơn vi',
            "desc" => 'Danh mục tài sản',
            'permission_childen' => [
                [
                    "name" => 'danh sách đơn vi',
                    "key_code" => 'unit-list',
                ],
                [
                    "name" => 'thêm đơn vi',
                    "key_code" => 'unit-add',
                ],
                [
                    "name" => 'sửa đơn vi',
                    "key_code" => 'unit-edit',
                ],
                [
                    "name" => 'xóa đơn vi',
                    "key_code" => 'unit-delete',
                ],
                [
                    "name" => 'chi tiết đơn vi',
                    "key_code" => 'unit-detail',
                ],
                [
                    "name" => 'in đơn vi',
                    "key_code" => 'unit-print',
                ],

            ],
        ],
        [
            "name" => 'Báo cáo thuê',
            "desc" => 'Báo cáo',
            'permission_childen' => [
                [
                    "name" => 'Tổng hợp hiện trạng thuê',
                    "key_code" => 'rental-status',
                ],
                [
                    "name" => 'khách thuê',
                    "key_code" => 'tenants',
                ],
                [
                    "name" => 'lịch sử hợp đồng',
                    "key_code" => 'contract-history',
                ],
            ],
        ],
        [
            "name" => 'Báo cáo thu',
            "desc" => 'Báo cáo',
            'permission_childen' => [
                [
                    "name" => 'Tổng hợp báo cáo thu',
                    "key_code" => 'report-all',
                ],
            ],
        ],
        [
            "name" => 'Báo cáo điện nước',
            "desc" => 'Báo cáo',
            'permission_childen' => [
                [
                    "name" => 'Báo cáo điện nước',
                    "key_code" => 'report-service-index',
                ],
                [
                    "name" => 'Báo cáo các dịch vụ khác',
                    "key_code" => 'report-project-service',
                ],
            ],
        ],
        [
            "name" => 'Báo cáo tài sản',
            "desc" => 'Báo cáo',
            'permission_childen' => [
                [
                    "name" => 'Báo cáo tồn kho tài sản',
                    "key_code" => 'asset-inventory',
                ],
                [
                    "name" => 'Báo cáo bảo trì sửa chữa',
                    "key_code" => 'maintenance-repair',
                ],
            ],
        ],
        [
            "name" => 'Dự án',
            "desc" => 'Quản lí Dự án',
            'permission_childen' => [
                [
                    "name" => 'danh sách Dự án',
                    "key_code" => 'project-list',
                ],
                [
                    "name" => 'Dự án - danh sách loại phòng',
                    "key_code" => 'type-room-list',
                ],
                [
                    "name" => 'Dự án - Danh sách dịch vụ dự án',
                    "key_code" => 'project-service-list',
                ],
                [
                    "name" => 'thêm Dự án',
                    "key_code" => 'project-add',
                ],
                [
                    "name" => 'sửa Dự án',
                    "key_code" => 'project-edit',
                ],
                [
                    "name" => 'xóa Dự án',
                    "key_code" => 'project-delete',
                ],
                [
                    "name" => 'chi tiết Dự án',
                    "key_code" => 'project-detail',
                ],
                [
                    "name" => 'in Dự án',
                    "key_code" => 'project-print',
                ],

            ],
        ],
        [
            "name" => 'Tòa nhà',
            "desc" => 'Quản lí tòa nhà',
            'permission_childen' => [
                [
                    "name" => 'danh sách tòa nhà',
                    "key_code" => 'building-list',
                ],
                [
                    "name" => 'Tòa nhà - danh sách Dự án',
                    "key_code" => 'project-list',
                ],
                [
                    "name" => 'thêm tòa nhà',
                    "key_code" => 'building-add',
                ],
                [
                    "name" => 'sửa tòa nhà',
                    "key_code" => 'building-edit',
                ],
                [
                    "name" => 'xóa tòa nhà',
                    "key_code" => 'building-delete',
                ],
                [
                    "name" => 'chi tiết tòa nhà',
                    "key_code" => 'building-detail',
                ],
                [
                    "name" => 'Tòa nhà - danh sách loại phòng',
                    "key_code" => 'type-room-list',
                ],
                [
                    "name" => 'Tòa nhà - danh sách phòng',
                    "key_code" => 'room-list',
                ],
                [
                    "name" => 'Tòa nhà - danh sách tầng',
                    "key_code" => 'floor-list',
                ],
                [
                    "name" => 'in tòa nhà',
                    "key_code" => 'building-print',
                ],

            ],
        ],
        [
            "name" => 'Tầng',
            "desc" => 'Quản lí tòa nhà',
            'permission_childen' => [
                [
                    "name" => 'danh sách tầng',
                    "key_code" => 'floor-list',
                ],
                [
                    "name" => 'thêm tầng',
                    "key_code" => 'floor-add',
                ],
                [
                    "name" => 'sửa tầng',
                    "key_code" => 'floor-edit',
                ],
                [
                    "name" => 'xóa tầng',
                    "key_code" => 'floor-delete',
                ],
                [
                    "name" => 'chi tiết tầng',
                    "key_code" => 'floor-detail',
                ],
                [
                    "name" => 'in tầng',
                    "key_code" => 'floor-print',
                ],

            ],
        ],
        [
            "name" => 'Loại phòng',
            "desc" => 'Quản lí Dự án',
            'permission_childen' => [
                [
                    "name" => 'danh sách loại phòng',
                    "key_code" => 'type-room-list',
                ],
                [
                    "name" => 'thêm loại phòng',
                    "key_code" => 'type-room-add',
                ],
                [
                    "name" => 'sửa loại phòng',
                    "key_code" => 'type-room-edit',
                ],
                [
                    "name" => 'xóa loại phòng',
                    "key_code" => 'type-room-delete',
                ],
                [
                    "name" => 'chi tiết loại phòng',
                    "key_code" => 'type-room-detail',
                ],
                [
                    "name" => 'in loại phòng',
                    "key_code" => 'type-room-print',
                ],

            ],
        ],
        [
            "name" => 'Dịch vụ dự án',
            "desc" => 'Quản lí Dự án',
            'permission_childen' => [
                [
                    "name" => 'Danh sách dịch vụ dự án',
                    "key_code" => 'project-service-list',
                ],
                [
                    "name" => 'Thêm dịch vụ dự án',
                    "key_code" => 'project-service-add',
                ],
                [
                    "name" => 'Sửa dịch vụ dự án',
                    "key_code" => 'project-service-edit',
                ],
                [
                    "name" => 'Xóa dịch vụ dự án',
                    "key_code" => 'project-service-delete',
                ],
                [
                    "name" => 'Chi tiết dịch vụ dự án',
                    "key_code" => 'project-service-detail',
                ],
                [
                    "name" => 'In dịch vụ dự án',
                    "key_code" => 'project-service-print',
                ],

            ],
        ],
        [
            "name" => 'Phòng',
            "desc" => 'Quản lí tòa nhà',
            'permission_childen' => [
                [
                    "name" => 'danh sách phòng',
                    "key_code" => 'room-list',
                ],
                [
                    "name" => 'thêm phòng',
                    "key_code" => 'room-add',
                ],
                [
                    "name" => 'sửa phòng',
                    "key_code" => 'room-edit',
                ],
                [
                    "name" => 'xóa phòng',
                    "key_code" => 'room-delete',
                ],
                [
                    "name" => 'chi tiết phòng',
                    "key_code" => 'room-detail',
                ],
                [
                    "name" => 'in phòng',
                    "key_code" => 'room-print',
                ],

            ],
        ],
        [
            "name" => 'Người dùng ',
            "desc" => 'Cài đặt người dùng',
            'permission_childen' => [
                [
                    "name" => 'danh sách nhân viên ',
                    "key_code" => 'user-list',
                ],
                [
                    "name" => 'thêm người dùng ',
                    "key_code" => 'user-add',
                ],
                [
                    "name" => 'sửa người dùng ',
                    "key_code" => 'user-edit',
                ],
                [
                    "name" => 'xóa người dùng ',
                    "key_code" => 'user-delete',
                ],
                [
                    "name" => 'chi tiết người dùng ',
                    "key_code" => 'user-detail',
                ],
                [
                    "name" => 'in người dùng ',
                    "key_code" => 'user-print',
                ],

            ],
        ],
        [
            "name" => 'Chức vụ',
            "desc" => 'Cài đặt người dùng',
            'permission_childen' => [
                [
                    "name" => 'danh sách chức vụ ',
                    "key_code" => 'role-list',
                ],
                [
                    "name" => 'thêm chức vụ ',
                    "key_code" => 'role-add',
                ],
                [
                    "name" => 'sửa chức vụ ',
                    "key_code" => 'role-edit',
                ],
                [
                    "name" => 'xóa chức vụ ',
                    "key_code" => 'role-delete',
                ],
                [
                    "name" => 'chi tiết chức vụ ',
                    "key_code" => 'role-detail',
                ]
            ],
        ],
        [
            "name" => 'phân quyền',
            "desc" => 'Cài đặt người dùng',
            'permission_childen' => [
                [
                    "name" => 'danh sách phân quyền',
                    "key_code" => 'permission-list',
                ],
                [
                    "name" => 'thêm phân quyền',
                    "key_code" => 'permission-add',
                ],
                [
                    "name" => 'chi tiết phân quyền',
                    "key_code" => 'permission-detail',
                ]
            ],
        ]

    ],
    
    'studentPermissions' => [
        'hop-dong-detail', 'invoice-contract-list', 'receipts-edit', 'Notify-list', 'Notify-detail'
    ]
];
