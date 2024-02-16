import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { UserType } from './shared/ models/usertype.model';

@Injectable({
  providedIn: 'root'
})

export class AppService {

  private url = 'http://localhost:9000';

  constructor(private http: HttpClient) { }

  getSubscribers(page: number){
    const options = { params: new HttpParams().set('page', page) };
    return this.http.get(`${this.url}/subscribers`, options);
  }

  addSubscriber(user: UserType) {
    return this.http.post(`${this.url}/subscribers`, user);
  }
}
