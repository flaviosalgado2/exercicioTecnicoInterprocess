<script>
export default {
  name: 'TopBar'
}
</script>

<template>
  <nav class="navbar navbar-top navbar-expand navbar-dashboard navbar-dark ps-0 pe-2 pb-0">
    <div class="container-fluid px-0">
      <div class="d-flex justify-content-end w-100" id="navbarSupportedContent">
        <ul class="navbar-nav align-items-center">
          <li class="nav-item">
            <div class="media d-flex align-items-center">
              <div class="avatar rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 32px; height: 32px;">
                U
              </div>
              <div class="media-body ms-2 text-dark align-items-center d-none d-lg-block">
                <span class="mb-0 font-small fw-bold text-gray-900">Usuário (Clínica ACME)</span>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>
