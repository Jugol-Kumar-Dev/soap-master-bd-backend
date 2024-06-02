<template>
    <div class="main-menu menu-fixed menu-accordion menu-shadow"
    :class="[
      { 'expanded': !isVerticalMenuCollapsed || (isVerticalMenuCollapsed && isMouseHovered) },
      themeSkin === 'light'|| themeSkin === 'bordered' ? 'menu-light' : 'menu-dark'
    ]"
    @mouseenter="updateMouseHovered(true)"
    @mouseleave="updateMouseHovered(false)"
    >
        <div class="navbar-header expanded">
            <slot
                name="header"
                :toggleVerticalMenuActive="toggleVerticalMenuActive"
                :toggleCollapsed="toggleCollapsed"
                :collapseTogglerIcon="collapseTogglerIcon"
            >
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item me-auto" style="max-width: 70%;">
                        <img src="@/Images/logo2.png" alt="" style="width:100%">

                    </li>

<!--                    <li class="nav-item nav-toggle">-->
<!--                        <a preserve-scroll class="nav-link modern-nav-toggle" href="javascript:void(0)">-->
<!--                            <Icon title="x" width="24" height="24" @click="toggleVerticalMenuActive" class="d-block d-lg-none" />-->
<!--                            <Icon :title="collapseTogglerIconFeather" width="24" height="24" @click="toggleCollapsed"-->
<!--                                  class="d-none d-xl-block collapse-toggle-icon" />-->
<!--                        </a>-->
<!--                    </li>-->
                </ul>
            </slot>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content scroll-area">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class="nav-item" v-if="$page.props.auth.user.role === 'instructor'">
                    <Link preserve-scroll class="d-flex align-items-center" href="/dashboard">
                        <Icon title="home" width="24" height="24" />
                        <span class="menu-title text-truncate">Dashboard</span>
                    </Link>
                </li>
                <li class="nav-item" v-if="$page.props.auth.user.role === 'admin'">
                    <Link preserve-scroll class="d-flex align-items-center" href="/dashboard">
                        <Icon title="home" width="24" height="24" />
                        <span class="menu-title text-truncate">Dashboard</span>
                    </Link>
                </li>
<!--                <li class="nav-item" v-if="$page.props.auth.user.role === 'admin'">
                    <Link preserve-scroll class="d-flex align-items-center" href="/chats">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        <span class="menu-title text-truncate">Chat</span>
                    </Link>
                </li>-->

                <li class="nav-item has-sub" :class="{'open' : clickMenu === 'Courses'}"  @click="toggleSubMenu('Courses')">
                    <a preserve-scroll class="d-flex align-items-center" href="javascript:void(0)">
                        <Icon title="book" />
                        <span class="menu-title text-truncate">Course Tools</span>
                    </a>

                    <ul class="menu-content">
                        <li>
                            <Link preserve-scroll class="d-flex align-items-center" href="/categories">
                                <Icon title="circle" width="24" height="24" />
                                <span class="menu-item text-truncate">Categories</span>
                            </Link>
                        </li>
                        <li>
                            <Link preserve-scroll class="d-flex align-items-center" href="/sub_categories">
                                <Icon title="circle" width="24" height="24" />
                                <span class="menu-item text-truncate">Sub Categories</span>
                            </Link>
                        </li>
                        <li>
                            <Link preserve-scroll class="d-flex align-items-center" href="/child_categories">
                                <Icon title="circle" width="24" height="24" />
                                <span class="menu-item text-truncate">Child Categories</span>
                            </Link>
                        </li>
<!--                        <li>-->
<!--                            <Link preserve-scroll class="d-flex align-items-center" href="/questions">-->
<!--                                <Icon title="circle" width="24" height="24" />-->
<!--                                <span class="menu-title text-truncate">Questions</span>-->
<!--                            </Link>-->
<!--                        </li>-->
                        <li>
                            <Link preserve-scroll class="d-flex align-items-center" href="/courses">
                                <Icon title="circle" width="24" height="24" />
                                <span class="menu-title text-truncate">Courses</span>
                            </Link>
                        </li>
<!--                        <li>-->
<!--                            <Link preserve-scroll class="d-flex align-items-center" href="/mocktests">-->
<!--                                <Icon title="circle" width="24" height="24" />-->
<!--                                <span class="menu-title text-truncate">Mocktests</span>-->
<!--                            </Link>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <Link preserve-scroll class="d-flex align-items-center" href="/api-mocktest">-->
<!--                                <Icon title="circle" width="24" height="24" />-->
<!--                                <span class="menu-title text-truncate">Api Mocktests</span>-->
<!--                            </Link>-->
<!--                        </li>-->
                    </ul>
                </li>

                <li class="nav-item has-sub" :class="{'open' : clickMenu === 'questions'}"  @click="toggleSubMenu('questions')">
                    <a preserve-scroll class="d-flex align-items-center" href="javascript:void(0)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><path fill="currentColor" d="M11.07 12.85c.77-1.39 2.25-2.21 3.11-3.44c.91-1.29.4-3.7-2.18-3.7c-1.69 0-2.52 1.28-2.87 2.34L6.54 6.96C7.25 4.83 9.18 3 11.99 3c2.35 0 3.96 1.07 4.78 2.41c.7 1.15 1.11 3.3.03 4.9c-1.2 1.77-2.35 2.31-2.97 3.45c-.25.46-.35.76-.35 2.24h-2.89c-.01-.78-.13-2.05.48-3.15M14 20c0 1.1-.9 2-2 2s-2-.9-2-2s.9-2 2-2s2 .9 2 2"/></svg>
                        <span class="menu-title text-truncate">Mock Tests</span>
                    </a>
                    <ul class="menu-content">
                        <li>
                            <Link preserve-scroll class="d-flex align-items-center" href="/questions-categories">
                                <Icon title="circle" width="24" height="24" />
                                <span class="menu-title text-truncate">Question Category</span>
                            </Link>
                        </li>
                        <li>
                            <Link preserve-scroll class="d-flex align-items-center" href="/questions">
                                <Icon title="circle" width="24" height="24" />
                                <span class="menu-title text-truncate">Questions</span>
                            </Link>
                        </li>
                        <li>
                            <Link preserve-scroll class="d-flex align-items-center" href="/mocktests">
                                <Icon title="circle" width="24" height="24" />
                                <span class="menu-title text-truncate">Mock Tests</span>
                            </Link>
                        </li>
                    </ul>
                </li>


                <li class=" nav-item">
                    <Link preserve-scroll class="d-flex align-items-center" href="/promos">
<!--                        <Icon title="chrome" width="24" height="24" />-->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-megaphone" viewBox="0 0 16 16">
                            <path d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0v-.214c-2.162-1.241-4.49-1.843-6.912-2.083l.405 2.712A1 1 0 0 1 5.51 15.1h-.548a1 1 0 0 1-.916-.599l-1.85-3.49-.202-.003A2.014 2.014 0 0 1 0 9V7a2.02 2.02 0 0 1 1.992-2.013 75 75 0 0 0 2.483-.075c3.043-.154 6.148-.849 8.525-2.199zm1 0v11a.5.5 0 0 0 1 0v-11a.5.5 0 0 0-1 0m-1 1.35c-2.344 1.205-5.209 1.842-8 2.033v4.233q.27.015.537.036c2.568.189 5.093.744 7.463 1.993zm-9 6.215v-4.13a95 95 0 0 1-1.992.052A1.02 1.02 0 0 0 1 7v2c0 .55.448 1.002 1.006 1.009A61 61 0 0 1 4 10.065m-.657.975 1.609 3.037.01.024h.548l-.002-.014-.443-2.966a68 68 0 0 0-1.722-.082z"/>
                        </svg>

                        <span class="menu-title text-truncate">Promos</span>
                    </Link>
                </li>

<!--
                <li class=" nav-item">
                    <Link preserve-scroll class="d-flex align-items-center" href="/subscriptions">
                        <Icon title="chrome" width="24" height="24" />
                        <span class="menu-title text-truncate">Subscriptions</span>
                    </Link>
                </li>-->
                <li class=" nav-item">
                    <Link preserve-scroll class="d-flex align-items-center" href="/transactions">
                        <Icon title="dollar-sign" width="24" height="24" />
                        <span class="menu-title text-truncate">Transactions</span>
                    </Link>
                </li>
                <li class=" nav-item">
                    <Link preserve-scroll class="d-flex align-items-center" href="/meetings">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><path fill="currentColor" d="M4 20q-.825 0-1.413-.588T2 18V6q0-.825.588-1.413T4 4h12q.825 0 1.413.588T18 6v4.5l4-4v11l-4-4V18q0 .825-.588 1.413T16 20H4Zm0-2h12V6H4v12Zm0 0V6v12Zm6-2l1.25-2.75L14 12l-2.75-1.25L10 8l-1.25 2.75L6 12l2.75 1.25L10 16Z"/></svg>
                        <span class="menu-title text-truncate">Zoom Meeting</span>
                    </Link>
                </li>
                <li class=" nav-item">
                    <Link preserve-scroll class="d-flex align-items-center" href="/coupons">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 10.192V4.808q0-.344.232-.576Q4.464 4 4.808 4h5.384q.344 0 .576.232q.232.232.232.576v5.384q0 .344-.232.576q-.232.232-.576.232H4.808q-.344 0-.576-.232Q4 10.536 4 10.192M5 10h5V5H5zm-1 9.192v-5.384q0-.344.232-.576q.232-.232.576-.232h5.384q.344 0 .576.232q.232.232.232.576v5.384q0 .344-.232.576q-.232.232-.576.232H4.808q-.344 0-.576-.232Q4 19.536 4 19.192M5 19h5v-5H5zm8-8.808V4.808q0-.344.232-.576q.232-.232.576-.232h5.384q.344 0 .576.232q.232.232.232.576v5.384q0 .344-.232.576q-.232.232-.576.232h-5.384q-.344 0-.576-.232q-.232-.232-.232-.576M14 10h5V5h-5zm4.25 10v-1.75H20V20zM13 14.75V13h1.75v1.75zm1.75 1.75v-1.75h1.75v1.75zM13 18.25V16.5h1.75v1.75zM14.75 20v-1.75h1.75V20zm1.75-1.75V16.5h1.75v1.75zm0-3.5V13h1.75v1.75zm1.75 1.75v-1.75H20v1.75z"/></svg><!--                        <Icon title="bar-chart" width="24" height="24" />-->
                        <span class="menu-title text-truncate">Coupon</span>
                    </Link>
                </li>



                <li class="nav-item has-sub" :class="{'open' : clickMenu === 'Setup'}"  @click="toggleSubMenu('Setup')">
                    <a preserve-scroll class="d-flex align-items-center" href="javascript:void(0)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-nintendo-switch" viewBox="0 0 16 16">
                            <path d="M9.34 8.005c0-4.38.01-7.972.023-7.982C9.373.01 10.036 0 10.831 0c1.153 0 1.51.01 1.743.05 1.73.298 3.045 1.6 3.373 3.326.046.242.053.809.053 4.61 0 4.06.005 4.537-.123 4.976-.022.076-.048.15-.08.242a4.14 4.14 0 0 1-3.426 2.767c-.317.033-2.889.046-2.978.013-.05-.02-.053-.752-.053-7.979m4.675.269a1.62 1.62 0 0 0-1.113-1.034 1.61 1.61 0 0 0-1.938 1.073 1.9 1.9 0 0 0-.014.935 1.63 1.63 0 0 0 1.952 1.107c.51-.136.908-.504 1.11-1.028.11-.285.113-.742.003-1.053M3.71 3.317c-.208.04-.526.199-.695.348-.348.301-.52.729-.494 1.232.013.262.03.332.136.544.155.321.39.556.712.715.222.11.278.123.567.133.261.01.354 0 .53-.06.719-.242 1.153-.94 1.03-1.656-.142-.852-.95-1.422-1.786-1.256"/>
                            <path d="M3.425.053a4.14 4.14 0 0 0-3.28 3.015C0 3.628-.01 3.956.005 8.3c.01 3.99.014 4.082.08 4.39.368 1.66 1.548 2.844 3.224 3.235.22.05.497.06 2.29.07 1.856.012 2.048.009 2.097-.04.05-.05.053-.69.053-7.94 0-5.374-.01-7.906-.033-7.952-.033-.06-.09-.063-2.03-.06-1.578.004-2.052.014-2.26.05Zm3 14.665-1.35-.016c-1.242-.013-1.375-.02-1.623-.083a2.81 2.81 0 0 1-2.08-2.167c-.074-.335-.074-8.579-.004-8.907a2.85 2.85 0 0 1 1.716-2.05c.438-.176.64-.196 2.058-.2l1.282-.003v13.426Z"/>
                        </svg>
                        <span class="menu-title text-truncate">Others Tools</span>
                    </a>

                    <ul class="menu-content">
                        <li class=" nav-item">
                            <Link preserve-scroll class="d-flex align-items-center" href="/sliders">
                                <Icon title="circle" width="24" height="24" />
                                <span class="menu-title text-truncate">Sliders</span>
                            </Link>
                        </li>

                        <li class=" nav-item">
                            <Link preserve-scroll class="d-flex align-items-center" href="/review">
                                <Icon title="circle" width="24" height="24" />
                                <span class="menu-title text-truncate">Review</span>
                            </Link>
                        </li>

                        <li class=" nav-item">
                            <Link preserve-scroll class="d-flex align-items-center" href="/blogs">
                                <Icon title="circle" width="24" height="24" />
                                <span class="menu-title text-truncate">Blog</span>
                            </Link>
                        </li>
                        <li class=" nav-item">
                            <Link preserve-scroll class="d-flex align-items-center" href="/support">
                                <Icon title="circle" width="24" height="24" />
                                <span class="menu-title text-truncate">Support</span>
                            </Link>
                        </li>
                        <li class=" nav-item">
                            <Link preserve-scroll class="d-flex align-items-center" href="/faqs">
                                <Icon title="circle" width="24" height="24" />
                                <span class="menu-title text-truncate">FAQ'S</span>
                            </Link>
                        </li>
                        <li class=" nav-item">
                            <Link preserve-scroll class="d-flex align-items-center" href="/pages">
                                <Icon title="circle" width="24" height="24" />
                                <span class="menu-title text-truncate">Pages</span>
                            </Link>
                        </li>
                    </ul>
                </li>



                <li class=" nav-item">
                    <Link preserve-scroll class="d-flex align-items-center" href="/settings">
                        <!--                        <Icon title="chrome" width="24" height="24" />-->
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                            <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                            <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
                        </svg>

                        <span class="menu-title text-truncate">Setting</span>
                    </Link>
                </li>

                <!--                <li class=" nav-item">
                                    <Link preserve-scroll class="d-flex align-items-center" href="/reports">
                                        <Icon title="bar-chart" width="24" height="24" />
                                        <span class="menu-title text-truncate">Reports</span>
                                    </Link>
                                </li>-->
                <li v-if="$page.props.auth.user.role === 'admin'" class="nav-item has-sub" :class="{'open' : clickMenu === 'Authentication'}" @click="toggleSubMenu('Authentication')">
                    <a preserve-scroll class="d-flex align-items-center" href="javascript:void(0)">
                        <Icon title="user-check" />
                        <span class="menu-title text-truncate">Authentication</span>
                    </a>
                    <ul class="menu-content">
                        <li>
                            <Link preserve-scroll class="d-flex align-items-center" href="/students">
                                <Icon title="circle" width="24" height="24" />
                                <span class="menu-item text-truncate">Student</span>
                            </Link>
                        </li>
<!--                        <li>
                            <Link preserve-scroll class="d-flex align-items-center" href="/instructors">
                                <Icon title="circle" width="24" height="24" />
                                <span class="menu-item text-truncate">Instructor</span>
                            </Link>
                        </li>-->
                        <li>
                            <Link preserve-scroll class="d-flex align-items-center" href="/admins">
                                <Icon title="circle" width="24" height="24" />
                                <span class="menu-item text-truncate">Admin</span>
                            </Link>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import Icon from '@/Components/Icon.vue';

const props = defineProps({
  isVerticalMenuActive: {
      type: Boolean,
      required: true,
    },
    toggleVerticalMenuActive: {
      type: Function,
      required: true,
    },
})
const isMouseHovered = ref(false)
const openClass = ref('')
const clickMenu = ref('')
const store = useStore()

const themeSkin = computed(() => store.state.skin)
const isVerticalMenuCollapsed = computed(() => store.state.isVerticalMenuCollapsed)

const collapseTogglerIconFeather = computed(() => (collapseTogglerIcon.value === 'unpinned' ? 'circle' : 'disc'))

onMounted(() => store.commit('UDATE_SKIN', themeSkin.value))

const collapseTogglerIcon = computed(() => {
    if (props.isVerticalMenuActive) {
      return isVerticalMenuCollapsed.value ? 'unpinned' : 'pinned'
    }
    return 'close'
  })

  const toggleCollapsed = () => {
      store.commit('UPDATE_MENU_COLLAPSED', !isVerticalMenuCollapsed.value)
  }

  const toggleSubMenu = (val) => {
      openClass.value = openClass.value ? '' : 'open'
      clickMenu.value = clickMenu.value === val ? '' : val
  }

  const updateMouseHovered = val => {
    isMouseHovered.value = val
  }
</script>
