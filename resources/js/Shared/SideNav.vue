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
                        <p>SOAP MASTER BD</p>
                    </li>
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
                <li class="nav-item" v-if="$page.props.auth.user.role === 'admin'">
                    <Link preserve-scroll class="d-flex align-items-center" href="/courses">
                        <Icon title="book" width="24" height="24" />
                        <span class="menu-title text-truncate">Courses</span>
                    </Link>
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



<!--                <li class=" nav-item">
                    <Link preserve-scroll class="d-flex align-items-center" href="/settings">
                        &lt;!&ndash;                        <Icon title="chrome" width="24" height="24" />&ndash;&gt;
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                            <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                            <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
                        </svg>

                        <span class="menu-title text-truncate">Setting</span>
                    </Link>
                </li>
                -->

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
