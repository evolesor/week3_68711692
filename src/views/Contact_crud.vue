```vue
<template>
  <div class="container mt-4">
    <h2 class="mb-3">รายชื่อลูกค้า</h2>

    <div class="mb-3">
      <button class="btn btn-primary" @click="openAddModal">
        Add <i class="bi bi-plus-circle"></i>
      </button>
    </div>

    <div v-if="loading" class="text-center">
      <p>กำลังโหลดข้อมูล...</p>
    </div>

    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <table class="table table-bordered table-striped">
      <thead class="table-primary">
        <tr>
          <th>รหัสการติดต่อ</th>
          <th>หัวข้อ</th>
          <th>รายละเอียด</th>
          <th>ชื่อ-นามสกุล</th>
          <th>Email</th>
          <th>วันเวลาที่เพิ่มข้อมูล</th>
          <th>แก้ไข/ลบ</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="contact in contacts" :key="contact.contact_id">
          <td>{{ contact.contact_id }}</td>
          <td>{{ contact.subject }}</td>
          <td>{{ contact.detail }}</td>
          <td>{{ contact.fullname }}</td>
          <td>{{ contact.email }}</td>
          <td>{{ contact.created_at }}</td>
          <td>
            <button
              class="btn btn-warning btn-sm"
              @click="openEditModal(contact)"
            >
              แก้ไข
            </button>
            |
            <button
              class="btn btn-danger btn-sm"
              @click="deleteContact(contact.contact_id)"
            >
              ลบ
            </button>
          </td>
        </tr>

        <tr v-if="!loading && contacts.length === 0">
          <td colspan="8" class="text-center">
            ไม่พบข้อมูลลูกค้า
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal เพิ่ม/แก้ไขข้อมูล -->
    <div class="modal fade" id="editModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ isEditMode ? "แก้ไขข้อมูลลูกค้า" : "เพิ่มลูกค้าใหม่" }}
            </h5>

            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
            ></button>
          </div>

          <div class="modal-body">
            <form @submit.prevent="saveContact">
              <div class="mb-3">
                <label class="form-label">หัวข้อ</label>
                <input
                  v-model="editContact.subject"
                  type="text"
                  class="form-control"
                  required
                />
              </div>

              <div class="mb-3">
                <label class="form-label">รายละเอียด</label>
                <textarea
                  v-model="editContact.detail"
                  class="form-control"
                  rows="3"
                  required
                ></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label">ชื่อ-นามสกุล</label>
                <input
                  v-model="editContact.fullname"
                  type="text"
                  class="form-control"
                  required
                />
              </div>

              <div class="mb-3">
                <label class="form-label">Email</label>
                <input
                  v-model="editContact.email"
                  type="email"
                  class="form-control"
                  required
                />
              </div>

              <button type="submit" class="btn btn-success">
                {{ isEditMode ? "บันทึกการแก้ไข" : "เพิ่มลูกค้า" }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";

export default {
  name: "ContactList",
  setup() {
    const contacts = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const editContact = ref({});
    const isEditMode = ref(false);
    let editModal = null;

    const fetchContacts = async () => {
      try {
        const response = await fetch("http://localhost/week3_68711692/php_api/contact_crud.php");
        const result = await response.json();

        if (result.success) {
          contacts.value = result.data;
        } else {
          error.value = result.message;
        }
      } catch (err) {
        error.value = err.message;
      } finally {
        loading.value = false;
      }
    };

    onMounted(() => {
      fetchContacts();
      const modalEl = document.getElementById("editModal");
      editModal = new window.bootstrap.Modal(modalEl);
    });

    // ✅ เปิด Modal เพิ่มลูกค้าใหม่
    const openAddModal = () => {
      isEditMode.value = false;
      editContact.value = {
        subject: "",
        detail: "",
        phone: "",
        email: "",
        fullname: ""
      };
      editModal.show();
    };

    // ✅ เปิด Modal แก้ไขลูกค้า
    const openEditModal = (contact) => {
      isEditMode.value = true;
      editContact.value = { ...contact, password: "" };
      editModal.show();
    };

    // ✅ ใช้ฟังก์ชันเดียวสำหรับทั้งเพิ่ม/แก้ไข
    const saveContact = async () => {
      const url = "http://localhost/week3_68711692/php_api/contact_crud.php";
      const method = isEditMode.value ? "PUT" : "POST";

      try {
        const response = await fetch(url, {
          method,
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(editContact.value)
        });

        const result = await response.json();

        if (result.success) {
          alert(result.message);
          fetchContacts();
          editModal.hide();
        } else {
          alert(result.message);
        }
      } catch (err) {
        alert("เกิดข้อผิดพลาด: " + err.message);
      }
    };

    // ✅ ลบลูกค้า
    const deleteContact = async (id) => {
      if (!confirm("คุณต้องการลบข้อมูลนี้ใช่หรือไม่?")) return;
      try {
        const response = await fetch("http://localhost/week3_68711692/php_api/contact_crud.php", {
          method: "DELETE",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ contact_id: id })
        });
        const result = await response.json();
        if (result.success) {
          contacts.value = contacts.value.filter(c => c.contact_id !== id);
          alert(result.message);
        } else {
          alert(result.message);
        }
      } catch (err) {
        alert("เกิดข้อผิดพลาด: " + err.message);
      }
    };

    return {
      contacts,
      loading,
      error,
      editContact,
      isEditMode,
      openAddModal,
      openEditModal,
      saveContact,
      deleteContact
    };
  }
};
</script>
