import { Component, OnInit } from '@angular/core';
import { NgxPaginationModule } from 'ngx-pagination';
import { AppService } from '../../app.service';
import { SubscribersListResponseType, SubscribersType } from '../../shared/ models/subscriber.model';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-list',
  standalone: true,
  imports: [NgxPaginationModule, RouterLink],
  templateUrl: './list.component.html',
  styleUrl: './list.component.scss'
})

export class ListComponent implements OnInit {  
  subscribers: Array<SubscribersType> = [];
  itemsPerPage: number = 0;
  currentPage: number = 1;
  totalItems: number = 0;
  loading: boolean = false;

  constructor(private service: AppService) {}

  ngOnInit(): void {
    this.fetchSubscribers(this.currentPage);
  }

  pageChangeEvent(page: number): void {
    this.fetchSubscribers(page);
  }

  fetchSubscribers(page: number): void {
    this.loading = true;
    this.service.getSubscribers(page)
      .subscribe((response) => {
        this.loading = false;
        const data = response as SubscribersListResponseType;
        this.totalItems = data.count;

        this.currentPage = data.currentPage;
        this.itemsPerPage = data.entriesPerPage;
        this.subscribers = data.data;
      });
  }
}
