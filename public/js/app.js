// ============================================
// WowoClean Dashboard - JavaScript Application
// ============================================

const API_BASE_URL = '/api/v1';
const API_GATEWAY = '/api/v1/gateway';

let authToken = null;
let currentUser = null;
let currentPage = 1;
let currentContainerId = null;
let deleteContainerId = null;
let searchTimeout;

// Axios Setup
axios.interceptors.request.use(config => {
    if (authToken) {
        config.headers.Authorization = `Bearer ${authToken}`;
    }
    return config;
});

axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            logout();
        }
        return Promise.reject(error);
    }
);

// ============================================
// INIT & NAVIGATION
// ============================================

document.addEventListener('DOMContentLoaded', () => {
    checkAuth();
});

function checkAuth() {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        window.location.href = '/login';
        return;
    }

    authToken = token;
    loadUserProfile();
    loadDashboard();
}

function navigateTo(page) {
    // Hide all pages
    document.querySelectorAll('.page-content').forEach(el => el.style.display = 'none');

    // Show selected page
    const pageElement = document.getElementById(page + 'Page');
    if (pageElement) {
        pageElement.style.display = 'block';
    }

    // Update active nav
    document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));
    event.target.closest('.nav-link')?.classList.add('active');

    // Load page data
    if (page === 'containers') {
        loadContainers();
    } else if (page === 'tracking-logs') {
        loadTrackingLogsTimeline();
    } else if (page === 'profile') {
        loadUserProfile();
    }
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const topbar = document.getElementById('topbar');
    const mainContent = document.getElementById('mainContent');

    sidebar.classList.toggle('collapsed');
    topbar.classList.toggle('collapsed-sidebar');
    mainContent.classList.toggle('collapsed-sidebar');
}

function toggleUserMenu() {
    const dropdown = document.getElementById('userMenuDropdown');
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

function toggleTheme() {
    // Placeholder untuk dark/light mode
    toast('Fitur tema akan segera hadir!', 'info');
}

function openSwagger() {
    window.open('/api/documentation', '_blank');
}

// ============================================
// AUTHENTICATION
// ============================================

function loadUserProfile() {
    axios.get(`${API_BASE_URL}/profile`)
        .then(response => {
            currentUser = response.data.user;
            updateUserUI();
        })
        .catch(error => {
            console.error('Error loading profile:', error);
        });
}

function updateUserUI() {
    if (!currentUser) return;

    const initial = currentUser.name.charAt(0).toUpperCase();
    document.getElementById('userName').textContent = currentUser.name;
    document.getElementById('userRole').textContent = currentUser.role === 'admin' ? 'Administrator' : 'Operator';
    document.getElementById('userAvatar').textContent = initial;

    document.getElementById('profileName').textContent = currentUser.name;
    document.getElementById('profileEmail').textContent = currentUser.email;
    document.getElementById('profileEmailDetail').textContent = currentUser.email;
    document.getElementById('profileRole').textContent = currentUser.role === 'admin' ? 'Administrator' : 'Operator';
    document.getElementById('profileRoleDetail').textContent = currentUser.role === 'admin' ? 'Administrator' : 'Operator';
    document.getElementById('profileAvatar').textContent = initial;
    document.getElementById('profileCreatedAt').textContent = new Date(currentUser.created_at).toLocaleDateString('id-ID');

    // Show/hide admin-only actions
    const adminOnly = document.getElementById('adminOnlyActions');
    if (adminOnly) {
        adminOnly.style.display = currentUser.role === 'admin' ? 'block' : 'none';
    }
}

function logout() {
    axios.post(`${API_BASE_URL}/logout`)
        .then(() => {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('current_user');
            window.location.href = '/login';
        })
        .catch(() => {
            localStorage.removeItem('auth_token');
            window.location.href = '/login';
        });
}

// ============================================
// DASHBOARD
// ============================================

async function loadDashboard() {
    try {
        const response = await axios.get(`${API_GATEWAY}/stats`);
        const stats = response.data.data;

        // Update stat cards
        document.getElementById('totalContainers').textContent = stats.total_containers;
        document.getElementById('activeContainers').textContent = stats.active;
        document.getElementById('maintenanceContainers').textContent = stats.maintenance;
        document.getElementById('archivedContainers').textContent = stats.archived;

        // Charts
        updateStatusChart(stats.status_distribution);
        updateActivityChart(stats.tracking_activity);

        // Recent logs
        updateRecentLogs(stats.recent_logs);
    } catch (error) {
        console.error('Error loading dashboard:', error);
    }
}

function updateRecentLogs(logs) {
    const tbody = document.getElementById('recentLogsBody');
    if (!logs || logs.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 40px;">
                    <div style="font-size: 24px; margin-bottom: 10px;"><i class="fas fa-inbox"></i></div>
                    <div>Tidak ada data aktivitas</div>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = logs.map(log => `
        <tr>
            <td>
                <strong>${log.container_kode}</strong>
            </td>
            <td>${log.lokasi}</td>
            <td>${log.operator}</td>
            <td>
                <span class="status-badge badge-${getStatusClass(log.status_perjalanan)}">
                    ${log.status_perjalanan}
                </span>
            </td>
            <td>${log.tanggal}</td>
        </tr>
    `).join('');
}

let statusChart = null;
let activityChart = null;

function updateStatusChart(data) {
    const ctx = document.getElementById('statusChart');
    if (!ctx) return;

    if (statusChart) {
        statusChart.destroy();
    }

    statusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Aktif', 'Perawatan', 'Penuh', 'Archived'],
            datasets: [{
                data: [data.Active, data.Maintenance, data.Full, data.Archived],
                backgroundColor: [
                    'rgba(0, 201, 167, 0.8)',
                    'rgba(255, 165, 2, 0.8)',
                    'rgba(255, 71, 87, 0.8)',
                    'rgba(168, 181, 200, 0.8)'
                ],
                borderColor: '#1a2a4a',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#e9ecef',
                        font: { size: 12 },
                        usePointStyle: true,
                    }
                }
            }
        }
    });
}

function updateActivityChart(data) {
    const ctx = document.getElementById('activityChart');
    if (!ctx) return;

    if (activityChart) {
        activityChart.destroy();
    }

    const dates = Object.keys(data).sort();
    const values = dates.map(date => data[date]);

    activityChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Jumlah Logs',
                data: values,
                borderColor: 'rgb(0, 212, 255)',
                backgroundColor: 'rgba(0, 212, 255, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: 'rgb(0, 212, 255)',
                pointBorderColor: '#1a2a4a',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#e9ecef',
                        font: { size: 12 }
                    }
                }
            },
            scales: {
                y: {
                    ticks: { color: '#a8b5c8' },
                    grid: { color: 'rgba(255,255,255,0.05)' }
                },
                x: {
                    ticks: { color: '#a8b5c8' },
                    grid: { color: 'rgba(255,255,255,0.05)' }
                }
            }
        }
    });
}

// ============================================
// CONTAINERS MANAGEMENT
// ============================================

async function loadContainers() {
    try {
        const search = document.getElementById('searchInput')?.value || '';
        const status = document.getElementById('statusFilter')?.value || '';
        const jenis = document.getElementById('jenisFilter')?.value || '';

        const response = await axios.get(`${API_GATEWAY}/containers`, {
            params: {
                search,
                status,
                jenis_limbah: jenis,
                page: currentPage,
                per_page: 10
            }
        });

        const { data, pagination } = response.data;
        updateContainersTable(data);
        updatePagination(pagination);
    } catch (error) {
        console.error('Error loading containers:', error);
        if (error.response?.status === 403) {
            navigateTo('unauthorized');
        }
    }
}

function updateContainersTable(containers) {
    const tbody = document.getElementById('containersTableBody');

    if (!containers || containers.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px;">
                    <div style="font-size: 24px; margin-bottom: 10px;"><i class="fas fa-inbox"></i></div>
                    <div>Tidak ada kontainer ditemukan</div>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = containers.map(container => {
        const isAdmin = currentUser?.role === 'admin';
        const actions = isAdmin ? `
            <div style="display: flex; gap: 8px;">
                <button class="btn btn-sm btn-secondary-custom" onclick="openEditContainerModal(${container.id})" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-primary-custom" onclick="openTrackingLogModal(${container.id})" title="Add Log">
                    <i class="fas fa-plus"></i>
                </button>
                <button class="btn btn-sm btn-danger-custom" onclick="openDeleteConfirm(${container.id})" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        ` : `
            <button class="btn btn-sm btn-primary-custom" onclick="openTrackingLogModal(${container.id})" title="View Logs">
                <i class="fas fa-history"></i>
            </button>
        `;

        return `
            <tr>
                <td><strong>${container.kode_container}</strong></td>
                <td>${container.jenis_limbah}</td>
                <td>${container.kapasitas}</td>
                <td>${container.lokasi}</td>
                <td>
                    <span class="status-badge badge-${getStatusClass(container.status)}">
                        ${container.status}
                    </span>
                </td>
                <td>
                    <span style="background: rgba(0, 212, 255, 0.1); padding: 4px 8px; border-radius: 4px; color: var(--secondary); font-size: 12px;">
                        ${container.tracking_logs_count} logs
                    </span>
                </td>
                <td>${actions}</td>
            </tr>
        `;
    }).join('');
}

function updatePagination(pagination) {
    const container = document.getElementById('paginationContainer');
    const pages = [];

    for (let i = 1; i <= pagination.last_page; i++) {
        const activeClass = i === pagination.current_page ? 'active' : '';
        pages.push(`
            <a href="#" class="pagination-link ${activeClass}" data-page="${i}" onclick="goToPage(${i}); return false;">
                ${i}
            </a>
        `);
    }

    container.innerHTML = pages.join('');
}

function goToPage(page) {
    currentPage = page;
    loadContainers();
}

function debounceSearch() {
    clearTimeout(searchTimeout);
    currentPage = 1;
    searchTimeout = setTimeout(loadContainers, 300);
}

function getStatusClass(status) {
    const map = {
        'Active': 'active',
        'Maintenance': 'maintenance',
        'Full': 'full',
        'Archived': 'archived'
    };
    return map[status] || 'active';
}

function openAddContainerModal() {
    document.getElementById('containerModalTitle').textContent = 'Tambah Kontainer';
    document.getElementById('containerForm').reset();
    currentContainerId = null;
    const modal = new bootstrap.Modal(document.getElementById('containerModal'));
    modal.show();
}

function openEditContainerModal(id) {
    currentContainerId = id;
    axios.get(`${API_GATEWAY}/containers/${id}`)
        .then(response => {
            const container = response.data.data;
            document.getElementById('containerModalTitle').textContent = 'Edit Kontainer';
            document.getElementById('kodeContainer').value = container.kode_container;
            document.getElementById('jenisLimbah').value = container.jenis_limbah;
            document.getElementById('kapasitas').value = container.kapasitas;
            document.getElementById('lokasi').value = container.lokasi;
            document.getElementById('status').value = container.status;

            const modal = new bootstrap.Modal(document.getElementById('containerModal'));
            modal.show();
        })
        .catch(error => {
            toast('Gagal memuat data kontainer', 'error');
        });
}

function saveContainer() {
    const form = document.getElementById('containerForm');
    const data = {
        kode_container: document.getElementById('kodeContainer').value,
        jenis_limbah: document.getElementById('jenisLimbah').value,
        kapasitas: parseInt(document.getElementById('kapasitas').value),
        lokasi: document.getElementById('lokasi').value,
        status: document.getElementById('status').value,
    };

    const request = currentContainerId
        ? axios.put(`${API_GATEWAY}/containers/${currentContainerId}`, data)
        : axios.post(`${API_GATEWAY}/containers`, data);

    request
        .then(response => {
            toast(response.data.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('containerModal')).hide();
            loadContainers();
        })
        .catch(error => {
            const errorMsg = error.response?.data?.errors || error.response?.data?.message || 'Terjadi kesalahan';
            toast(JSON.stringify(errorMsg), 'error');
        });
}

function openDeleteConfirm(id) {
    deleteContainerId = id;
    const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    modal.show();
}

function confirmDelete() {
    if (!deleteContainerId) return;

    axios.delete(`${API_GATEWAY}/containers/${deleteContainerId}`)
        .then(response => {
            toast(response.data.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
            loadContainers();
        })
        .catch(error => {
            toast(error.response?.data?.message || 'Gagal menghapus kontainer', 'error');
        });
}

// ============================================
// TRACKING LOGS
// ============================================

function openTrackingLogModal(containerId) {
    document.getElementById('trackingContainerId').value = containerId;
    document.getElementById('trackingLogForm').reset();
    const modal = new bootstrap.Modal(document.getElementById('trackingLogModal'));
    modal.show();
}

function saveTrackingLog() {
    const containerId = document.getElementById('trackingContainerId').value;
    const data = {
        tanggal: document.getElementById('trackingTanggal').value,
        lokasi: document.getElementById('trackingLokasi').value,
        status_perjalanan: document.getElementById('trackingStatus').value,
        operator: document.getElementById('trackingOperator').value,
        catatan: document.getElementById('trackingCatatan').value,
    };

    axios.post(`${API_GATEWAY}/containers/${containerId}/tracking-logs`, data)
        .then(response => {
            toast(response.data.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('trackingLogModal')).hide();
            loadTrackingLogsTimeline();
        })
        .catch(error => {
            const errorMsg = error.response?.data?.errors || error.response?.data?.message || 'Terjadi kesalahan';
            toast(JSON.stringify(errorMsg), 'error');
        });
}

async function loadTrackingLogsTimeline() {
    try {
        const response = await axios.get(`${API_GATEWAY}/containers?per_page=100`);
        const containers = response.data.data;
        let allLogs = [];

        // Collect all tracking logs from all containers
        for (const container of containers) {
            try {
                const logsResponse = await axios.get(`${API_GATEWAY}/containers/${container.id}/tracking-logs`);
                allLogs = allLogs.concat(logsResponse.data.data);
            } catch (e) {
                continue;
            }
        }

        // Sort by date descending
        allLogs.sort((a, b) => new Date(b.tanggal) - new Date(a.tanggal));

        updateTrackingTimeline(allLogs);
    } catch (error) {
        console.error('Error loading tracking logs:', error);
    }
}

function updateTrackingTimeline(logs) {
    const timeline = document.getElementById('trackingLogsTimeline');

    if (!logs || logs.length === 0) {
        timeline.innerHTML = `
            <div style="text-align: center; color: var(--text-muted); padding: 40px;">
                <i class="fas fa-inbox" style="font-size: 40px; margin-bottom: 10px; display: block;"></i>
                Tidak ada tracking log
            </div>
        `;
        return;
    }

    timeline.innerHTML = logs.map(log => `
        <div class="timeline-item">
            <div class="timeline-dot">
                <i class="fas fa-map-pin"></i>
            </div>
            <div class="timeline-content">
                <h5><i class="fas fa-cube"></i> ${log.container?.kode_container || 'Unknown'}</h5>
                <p><strong>Lokasi:</strong> ${log.lokasi}</p>
                <p><strong>Catatan:</strong> ${log.catatan || '-'}</p>
                <div class="meta">
                    <span><i class="fas fa-calendar"></i> ${new Date(log.tanggal).toLocaleDateString('id-ID')}</span>
                    <span><i class="fas fa-user"></i> ${log.operator}</span>
                    <span class="status-badge badge-${getStatusClass(log.status_perjalanan)}">
                        ${log.status_perjalanan}
                    </span>
                </div>
            </div>
        </div>
    `).join('');
}

// ============================================
// NOTIFICATIONS
// ============================================

function toast(message, type = 'info') {
    const container = document.querySelector('.toast-container') || createToastContainer();
    const iconMap = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    };

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <i class="toast-icon ${type} ${iconMap[type]}"></i>
        <span>${message}</span>
        <button class="toast-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;

    container.appendChild(toast);

    setTimeout(() => toast.remove(), 5000);
}

function createToastContainer() {
    const container = document.createElement('div');
    container.className = 'toast-container';
    document.body.appendChild(container);
    return container;
}
