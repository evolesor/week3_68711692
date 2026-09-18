<template>
  <div class="container mt-4">
    <!-- หัวข้อหน้า -->
    <h2 class="mb-3">ติดต่อเรา</h2>
    
    <!-- ตารางแสดงข้อมูลลูกค้า -->
    <div class="text-end mb-3">
    <a href="/add_contact" class="btn btn-primary" >Add+</a> 
    </div>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>รหัสการติดต่อ</th>        <!-- index -->
          <th>หัวข้อ</th>     <!-- customer_id -->
          <th>รายละเอียด</th>            <!-- firstName -->
          <th>ชื่อ-นามสกุล</th>        <!-- lastName -->
          <th>Email</th>       <!-- phone -->
          <th>วันเวลาที่เพิ่มข้อมูล</th>      <!-- username -->
        </tr>
      </thead>

      <tbody>
        <!-- วนลูปข้อมูล customers -->
        <tr v-for="(item,index) in contacts" :key="item.contact_id">
          <td>{{ index + 1 }}</td>       <!-- แสดงลำดับที่ (เริ่มจาก 1) -->
          <td>{{ item.subject }}</td> <!-- รหัสลูกค้า -->
          <td>{{ item.detail }}</td>   <!-- ชื่อ -->
          <td>{{ item.fullname }}</td>    <!-- นามสกุล -->
          <td>{{ item.email}}</td>       <!-- เบอร์โทร -->
          <td>{{ item.created_at}}</td> 
        </tr>
      </tbody>
    </table>

    <!-- Loading: แสดงระหว่างรอข้อมูล -->
    <div v-if="loading" class="text-center">
      <p>กำลังโหลดข้อมูล...</p>
    </div>

    <!-- Error: แสดงเมื่อเกิดข้อผิดพลาด -->
    <div v-if="error" class="alert alert-danger">
      {{ error }}
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

    const fetchdata = async () => {
      try {
        const response = await fetch(
          "http://localhost/week3_68711692/php_api/show_contact.php"
        );

        if (!response.ok) {
          throw new Error("ไม่สามารถดึงข้อมูลได้");
        }

        contacts.value = await response.json();

      } catch (err) {
        error.value = err.message;

      } finally {
        loading.value = false;
      }
    };

    onMounted(() => {
      fetchdata();
    });

    return {
      contacts,
      loading,
      error
    };
  }
};
</script>
