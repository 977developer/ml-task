import { Component, inject } from '@angular/core';
import { Validators, FormControl, FormGroup, FormsModule, ReactiveFormsModule } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { AppService } from '../../app.service';
import { UserType } from '../../shared/ models/usertype.model';
import { JsonPipe } from '@angular/common';

@Component({
  selector: 'app-create',
  standalone: true,
  imports: [FormsModule, RouterLink, ReactiveFormsModule, JsonPipe],
  templateUrl: './create.component.html',
  styleUrl: './create.component.scss'
})
export class CreateComponent {
  router = inject(Router);

  subscriberForm = new FormGroup({
    firstName: new FormControl('', [
      Validators.required
    ]),
    lastName: new FormControl('', [
      Validators.required
    ]),
    email: new FormControl('', [
      Validators.required,
      Validators.pattern("^[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,4}$")]
    ),
    status: new FormControl(1, [
      Validators.required
    ]),
  });

  user: UserType = {
    firstName : '',
    lastName : '',
    email : '',
    status : 1
  };

  errMsg: boolean = false;
  addSuccess: boolean = false;

  constructor(private service: AppService) {}

  get firstName(){
    return this.subscriberForm.get('firstName');
  }
  
  get lastName(){
    return this.subscriberForm.get('lastName');
  }

  get email(){
    return this.subscriberForm.get('email');
  }

  handleSubmit(): void {
    if (!this.subscriberForm.valid) {
      return;
    }
    this.service.addSubscriber(this.subscriberForm.value as UserType)
      .subscribe({
        next: () => { this.addSuccess = true; this.errMsg = false},
        error: (e) => { this.addSuccess = false; this.errMsg = e.error }
      });
  }
}
