export interface SubscribersType {
    id: number;
    firstName: string;
    lastName: string;
    email: string;
    status: boolean;
}
  
export interface SubscribersListResponseType {
    count: number;
    currentPage: number;
    entriesPerPage : number;
    data : Array<SubscribersType>
}
