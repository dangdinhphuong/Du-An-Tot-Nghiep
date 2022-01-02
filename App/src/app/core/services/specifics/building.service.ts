import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService, CommonService } from '../commons';

@Injectable({
    providedIn: 'root'
})
export class BuildingService extends CommonService {

    constructor(apiService: ApiService) {
        super(apiService);
        this.tableName = 'buildings';
    }
}
