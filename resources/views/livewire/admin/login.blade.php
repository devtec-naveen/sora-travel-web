<div>
   <form wire:submit="login" method="POST">
      @csrf
      <x-form.input type="text" name="email" label="Email Address" placeholder="Enter email"/>
      <x-form.input type="password" name="password" label="Password" placeholder="Enter Password"/>
      <div class="terms-box d-flex align-items-center justify-content-between mt-3">         
         <label class="custom-checkbox">
            <input type="checkbox" name="remember">
            <p>Remember me</p>
         </label>        
      </div>
        <div class="mt-4">
            <button type="submit"
                class="btn ripple btn-main-primary btn-block"
                wire:loading.attr="disabled">
                <span wire:loading.remove>
                    Sign In
                </span>
                <span wire:loading>
                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                    Loading...
                </span>
            </button>
        </div>
   </form>
</div>