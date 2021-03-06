import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { EditUserComponent } from '../edit-user/edit-user.component';
import { CreateUserComponent } from '../create-user/create-user.component';
import { IRole, IUser, LoadingService, UserService } from 'src/app/core';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { RoleService } from 'src/app/core/services/specifics/role.service';
import { ToastrService } from 'ngx-toastr';
import { MatSlideToggleChange } from '@angular/material/slide-toggle';
import { DetailUserComponent } from '../detail-user/detail-user.component';

@Component({
  selector: 'app-user',
  templateUrl: './user.component.html',
  styleUrls: ['./user.component.css'],
})
export class UserComponent implements OnInit {
  roles: IRole[] = [];
  displayedColumns: string[] = [
    'id',
    'fullname',
    'email',
    'status',
    'role_id',
    'action',
  ];
  staffs: IUser[] = [];
  formSearch: FormGroup;
  lastPage: any = null;
  totalItems: number = 0;

  controlStatus: FormControl;

  constructor(
    public dialog: MatDialog,
    private userService: UserService,
    private roleService: RoleService,
    private loadingService: LoadingService,
    private fb: FormBuilder,
    private toast: ToastrService
  ) {
    this.formSearch = this.fb.group({
      name: '',
      role_id: '',
      status: '',
      page: '',
    });
    this.controlStatus = this.fb.control('');
  }

  get f() {
    return this.formSearch.controls;
  }

  ngOnInit(): void {
    this.loadingService.setLoading(true);
    this.roleService.getAllRoles().subscribe((t) => {
      if (t.data[0]?.id) {
        this.roles = t.data;
      }
    });
    this.getStaffs();
  }

  openDialogEdit(staff: IUser): void {
    const dialogRef = this.dialog.open(EditUserComponent, {
      width: '750px',
      disableClose: false,
      data: { roles: this.roles, staff: staff },
    });
    dialogRef.afterClosed().subscribe((data) => {
      if (data == 'isReload') {
        this.getStaffs(this.formSearch.value);
      }
    });
  }
  openDialogCreate(): void {
    const dialogRef = this.dialog.open(CreateUserComponent, {
      width: '750px',
      disableClose: false,
      data: { roles: this.roles },
    });
    dialogRef.afterClosed().subscribe((data) => {
      if (data == 'isReload') {
        this.getStaffs(this.formSearch.value);
      }
    });
  }
  openDialogDetail(staff: IUser) {
    const dialogRef = this.dialog.open(DetailUserComponent, {
      width: '750px',
      disableClose: false,
      data: { roles: this.roles, staff: staff },
    });
  }

  removeStaff(maintain: IUser) {
    if (window.confirm('X??c nh???n x??a?')) {
      this.loadingService.setLoading(true);
      this.userService.delete(maintain.id).subscribe(
        (t) => {
          this.getStaffs(this.formSearch.value);
          this.toast.success('X??a t??i kho???n th??nh c??ng', 'Th??ng b??o', {
            timeOut: 3000,
            closeButton: true,
          });
        },
        (f) => {
          this.loadingService.setLoading(false);
          this.toast.error('X??a t??i kho???n th???t b???i', 'Th??ng b??o', {
            timeOut: 3000,
            closeButton: true,
          });
        }
      );
    }
  }

  getStaffs(searchData: any = null) {
    if (searchData !== null) {
      for (let key in searchData) {
        if (!searchData[key]) {
          delete searchData[key];
        }
      }
    }
    this.loadingService.setLoading(true);
    this.userService.getStaffs(searchData).subscribe((t) => {
      this.staffs = t.data.user.data; // Set data cho table
      this.lastPage = t.data.user.last_page; // set last_page cho pagination
      this.totalItems = t.data.user.total; // Set t???ng item cho pagination
      this.formSearch.patchValue({ page: t.data.user.current_page }); // Set control page
      this.loadingService.setLoading(false);
    });
  }

  changePage(currentPage) {
    if (this.formSearch.value.page !== currentPage) {
      this.formSearch.patchValue({ page: currentPage });
      this.getStaffs(this.formSearch.value);
    }
  }

  searchStaff() {
    let searchData = this.formSearch.value;
    delete searchData.page;
    this.getStaffs(searchData);
  }

  updateStatus({ status, id }: any, event: MatSlideToggleChange) {
    if (
      window.confirm(
        `${(status ? false : true)
          ? 'X??c nh???n k??ch ho???t t??i kho???n?'
          : 'X??c nh???n v?? hi???u h??a kho???n?'
        }`
      )
    ) {
      this.userService
        .activeUser({ status: status ? '0' : 1, id: id })
        .subscribe(
          (t) => {
            this.getStaffs(this.formSearch.value);
            this.toast.success(
              `${!status
                ? 'K??ch ho???t t??i kho???n th??nh c??ng'
                : 'V?? hi???u h??a kho???n th??nh c??ng'
              }`,
              'Th??ng b??o',
              {
                timeOut: 3000,
                closeButton: true,
              }
            );
          },
          (f) => {
            this.loadingService.setLoading(false);
            event.source.checked = status ? true : false;
            this.toast.error(
              `${!status
                ? 'K??ch ho???t t??i kho???n th???t b???i'
                : 'V?? hi???u h??a kho???n th???t b???i'
              }`,
              'Th??ng b??o',
              {
                timeOut: 3000,
                closeButton: true,
              }
            );
          }
        );
    } else {
      event.source.checked = status ? true : false;
    }
  }
}
