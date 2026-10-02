<template>
  <div class="container mt-4">
    <!-- หัวข้อหน้า -->
    <h2 class="mb-3">รายชื่อพนักงาน</h2>
    
    <!-- ตารางแสดงข้อมูลพนักงาน -->
    <div class="text-end mb-3">
    <a href="/add_employee" class="btn btn-primary" >Add+</a> 
    </div>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>ลำดับที่</th>        <!-- index -->
          <th>รหัสพนักงาน</th>     <!-- emp_id -->
          <th>ชื่อ</th>            <!-- firstName -->
          <th>นามสกุล</th>        <!-- lastName -->
          <th>เบอร์โทร</th>       <!-- phone -->
          <th>ชื่อผู้ใช้</th>      <!-- username -->
          <th>ลบ</th>
        </tr>
      </thead>

      <tbody>
        <!-- วนลูปข้อมูล employees -->
        <tr v-for="(item,index) in employees" :key="item.emp_id">
          <td>{{ index + 1 }}</td>       <!-- แสดงลำดับที่ (เริ่มจาก 1) -->
          <td>{{ item.emp_id }}</td> <!-- รหัสพนักงาน -->
          <td>{{ item.firstName }}</td>   <!-- ชื่อ -->
          <td>{{ item.lastName }}</td>    <!-- นามสกุล -->
          <td>{{ item.phone }}</td>       <!-- เบอร์โทร -->
          <td>{{ item.username }}</td>    <!-- ชื่อผู้ใช้ -->
          <td>
            <button class="btn btn-danger btn-sm" @click="deleteEmployee(item.emp_id)">ลบ</button>
          </td>
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
// import ฟังก์ชันจาก Vue (Composition API)
import { ref, onMounted } from "vue";

export default {
  name: "EmployeeList", // ชื่อ component

  setup() {
    // -----------------------------
    // state (ตัวแปร reactive)
    // -----------------------------
    const employees = ref([]); // เก็บข้อมูลพนักงาน (array)
    const loading = ref(true); // สถานะโหลดข้อมูล
    const error = ref(null);   // เก็บ error

    // -----------------------------
    // ฟังก์ชันดึงข้อมูลจาก API
    // -----------------------------
    const fetchdata = async () => {
      try {
        // เรียก API (PHP)
        const response = await fetch("http://localhost/week3_68711692/php_api/show_employee.php");

        // ตรวจสอบว่าการเรียกสำเร็จหรือไม่
        if (!response.ok) {
          throw new Error("ไม่สามารถดึงข้อมูลได้");
        }

        // แปลง response เป็น JSON
        employees.value = await response.json();

      } catch (err) {
        // ถ้า error ให้เก็บข้อความไว้แสดง
        error.value = err.message;

      } finally {
        // ไม่ว่าจะสำเร็จหรือ error ให้หยุด loading
        loading.value = false;
      }
    };


    // -----------------------------
    // lifecycle: ทำงานเมื่อ component โหลดเสร็จ
    // -----------------------------
    onMounted(() => {
      fetchdata(); // เรียก API ทันที
    });

    const deleteEmployee = async (empId) => {
      if (!confirm("คุณต้องการลบข้อมูลนี้ใช่หรือไม่?")) return;

      try {
        const response = await fetch("http://localhost/week3_68711692/php_api/api_employee.php", {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({ emp_id: empId })
        });

        const result = await response.json();

        if (result.success) {
          employees.value = employees.value.filter(employee => employee.emp_id !== empId);
          alert(result.message);
        } else {
          alert(result.message);
        }

      } catch (err) {
        alert("เกิดข้อผิดพลาด: " + err.message);
      }
    };
    // -----------------------------
    // return ค่าไปใช้ใน template
    // -----------------------------
    return {
      employees,
      deleteEmployee,
      loading,
      error
    };
  }
};
</script>