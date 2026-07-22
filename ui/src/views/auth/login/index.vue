<!-- 登录页面 -->
<template>
  <div class="login-page flex w-full h-screen" :class="`login-layout-${loginLayout}`">
    <div class="login-page-logo">
      <ArtLogo class="icon" size="46" />
      <h1 class="title">{{ systemName }}</h1>
    </div>

    <AuthTopBar
      fixed-to-viewport
      show-login-layout
      v-model:login-layout="loginLayout"
    />

    <LoginLeftView class="login-bg" hide-logo :center-mode="loginLayout === 'center'" />

    <div class="login-main">
      <div class="auth-right-wrap">
        <div class="form">
          <h3 class="title">{{ $t('login.title') }}</h3>
          <p class="sub-title">{{ $t('login.subTitle') }}</p>
          <ElForm
            ref="formRef"
            :model="formData"
            :rules="rules"
            :key="formKey"
            @keyup.enter="handleSubmit"
            class="login-form"
          >
            <ElFormItem prop="username">
              <ElInput
                class="custom-height"
                :placeholder="$t('login.placeholder.username')"
                v-model.trim="formData.username"
              />
            </ElFormItem>
            <ElFormItem prop="password">
              <ElInput
                class="custom-height"
                :placeholder="$t('login.placeholder.password')"
                v-model.trim="formData.password"
                type="password"
                autocomplete="off"
                show-password
              />
            </ElFormItem>
            <ElFormItem prop="code">
              <ElInput
                class="custom-height"
                :placeholder="$t('login.placeholder.code')"
                v-model.trim="formData.code"
                type="text"
                autocomplete="off"
              >
                <template #append>
                  <img
                    :src="captcha"
                    style="height: 36px; cursor: pointer"
                    @click="refreshCaptcha"
                  />
                </template>
              </ElInput>
             
            </ElFormItem>

            <div class="flex-cb mt-2 text-sm">
              <ElCheckbox v-model="formData.rememberPassword">{{
                $t('login.rememberPwd')
              }}</ElCheckbox>
              <!-- <RouterLink class="text-theme" :to="{ name: 'ForgetPassword' }">{{
                $t('login.forgetPwd')
              }}</RouterLink> -->
            </div>

            <div class="login-submit-wrap">
              <ElButton
                class="w-full custom-height"
                type="primary"
                @click="handleSubmit"
                :loading="loading"
                v-ripple
              >
                {{ $t('login.btnText') }}
              </ElButton>
            </div>

            <!-- <div class="mt-5 text-sm text-gray-600">
              <span>{{ $t('login.noAccount') }}</span>
              <RouterLink class="text-theme" :to="{ name: 'Register' }">{{
                $t('login.register')
              }}</RouterLink>
            </div> -->
          </ElForm>
        </div>
      </div>
    </div>

    <ElDialog
      v-if="loginWindowEnabled"
      v-model="welcomeDialogVisible"
      title="eqrayadmin"
      width="750px"
	  height="450px"
      align-center
      :close-on-click-modal="false"
    >
      <ElTabs v-model="activeTab" type="card">
        <ElTabPane label="PHP版本" name="php">
          <div class="welcome-dialog">
            <p class="welcome-dialog__intro">
              eqrayadmin PHP 版本，基于自研 eqrayphp 框架，一个后台适配多个前端系统。
            </p>
            <ul class="welcome-dialog__list">
              <li>
                适配 Art Design Pro：
                <a href="https://v3.phpframe.org" target="_blank" rel="noopener noreferrer">https://v3.phpframe.org</a>
                <span class="welcome-dialog__tag">（本演示，已开源）</span>
              </li>
              <li>
                适配 SoyBeanAdmin：
                <a href="https://v4.phpframe.org" target="_blank" rel="noopener noreferrer">https://v4.phpframe.org</a>
                <span class="welcome-dialog__tag">（已开源）</span>
              </li>
              <li>
                适配 Vben5-Ele：
                <a href="https://v5.phpframe.org" target="_blank" rel="noopener noreferrer">https://v5.phpframe.org</a>
              </li>
            </ul>
            <p class="welcome-dialog__footer">目前基础底座已开源，欢迎 Star：</p>
            <div class="welcome-dialog__links">
              <a href="https://gitee.com/fsscms/eqrayadmin" target="_blank" rel="noopener noreferrer">Gitee</a>
              <a href="https://github.com/xuey490/eqrayadmin" target="_blank" rel="noopener noreferrer">GitHub</a>
            </div>
          </div>
        </ElTabPane>
        <ElTabPane label="NestJs版本" name="nestjs">
          <div class="welcome-dialog">
            <p class="welcome-dialog__intro">
              eqrayadmin(NestJs)是一款基于NestJS + TypeScript6 + TypeORM1.0等 技术栈的全功能企业级后端管理系统。
            </p>
            <ul class="welcome-dialog__list">
              <li>
                适配 Art Design Pro：
                <a href="https://nest.phpframe.org" target="_blank" rel="noopener noreferrer">https://nest.phpframe.org</a>
              </li>
			  经测试可以几乎可以无损适配php版本的SoyBeanAdmin，Vben5-Ele的前端
            </ul>
            <p class="welcome-dialog__footer">NestJs 版本已开源，欢迎关注：</p>
            <div class="welcome-dialog__links">
              <a href="https://gitee.com/eqrayphp_Team/fss-admin_nest" target="_blank" rel="noopener noreferrer">Gitee</a>
              <a href="https://github.com/xuey490/eqrayadmin_NestJs" target="_blank" rel="noopener noreferrer">GitHub</a>
            </div>
          </div>
        </ElTabPane>
        <ElTabPane label="Python版本" name="python">
          <div class="welcome-dialog">
            <p class="welcome-dialog__intro">
              eqrayadmin Python 版本，基于 FastAPI + SQLAlchemy 异步架构，高性能 Python 全栈后台解决方案。
            </p>
            <ul class="welcome-dialog__list">
              <li>
                适配 Art Design Pro：
                <a href="https://fast.phpframe.org" target="_blank" rel="noopener noreferrer">https://fast.phpframe.org</a>
              </li>

            </ul>
            <p class="welcome-dialog__footer">Python 版本规划中，欢迎关注：</p>
            <div class="welcome-dialog__links">
				测试地址暂不可用，期待...
            </div>
          </div>
        </ElTabPane>
      </ElTabs>
      <template #footer>
        <ElButton type="primary" @click="welcomeDialogVisible = false">知道了</ElButton>
      </template>
    </ElDialog>
  </div>
</template>

<script setup lang="ts">
  import AppConfig from '@/config'
  import { useUserStore } from '@/store/modules/user'
  import { useI18n } from 'vue-i18n'
  import { HttpError } from '@/utils/http/error'
  import {
    fetchCaptcha,
    fetchLogin,
    fetchGetUserInfo,
    fetchPublicConfigValue
  } from '@/api/auth'
  import { ElNotification, type FormInstance, type FormRules } from 'element-plus'

  defineOptions({ name: 'Login' })

  type LoginLayout = 'center' | 'left' | 'right'
  const loginLayout = ref<LoginLayout>('center')

  const { t, locale } = useI18n()
  const formKey = ref(0)

  // 监听语言切换，重置表单
  watch(locale, () => {
    formKey.value++
  })

  const userStore = useUserStore()
  const router = useRouter()

  const captcha = ref(
    'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'
  )

  const systemName = ref(AppConfig.systemInfo.name)
  const formRef = ref<FormInstance>()

  const formData = reactive({
    username: 'admin',
    password: '123456',
    code: '1234',
    uuid: '',
    rememberPassword: true
  })

  const rules = computed<FormRules>(() => ({
    username: [{ required: true, message: t('login.placeholder.username'), trigger: 'blur' }],
    password: [{ required: true, message: t('login.placeholder.password'), trigger: 'blur' }],
    code: [{ required: true, message: t('login.placeholder.code'), trigger: 'blur' }]
  }))

  const loading = ref(false)
  const loginWindowEnabled = ref(false)
  const welcomeDialogVisible = ref(false)
  const activeTab = ref('php')
  let welcomeDialogTimer: ReturnType<typeof setTimeout> | undefined


  const loadLoginWindowConfig = async () => {
    try {
      const res = await fetchPublicConfigValue('login_window')
      loginWindowEnabled.value = res?.value === '1'
    } catch (error) {
      console.error('[Login] 加载登录弹窗配置失败:', error)
      loginWindowEnabled.value = false
    }
  }

  onMounted(() => {
    loadLoginWindowConfig()
    refreshCaptcha()
  })

  // 登录弹窗延迟显示
  watch(loginWindowEnabled, (enabled) => {
    if (enabled) {
      welcomeDialogTimer = setTimeout(() => {
        welcomeDialogVisible.value = true
      }, 2000)
    }
  })

  onUnmounted(() => {
    if (welcomeDialogTimer) {
      clearTimeout(welcomeDialogTimer)
    }
  })

  // 登录
  const handleSubmit = async () => {
    if (!formRef.value) return

    try {
      // 表单验证
      const valid = await formRef.value.validate()
      if (!valid) return

      loading.value = true

      // 登录请求
      const { access_token, refresh_token } = await fetchLogin({
        username: formData.username,
        password: formData.password,
        code: formData.code,
        uuid: formData.uuid
      })

      // 验证token
      if (!access_token) {
        throw new Error('Login failed - no token received')
      }

      // 存储token和用户信息
      userStore.setToken(access_token, refresh_token)
      const userInfo = await fetchGetUserInfo()
      userStore.setUserInfo(userInfo)
      userStore.setLoginStatus(true)

      // 登录成功处理
      showLoginSuccessNotice()
      router.push('/')
    } catch (error) {
      // 处理 HttpError
      if (error instanceof HttpError) {
        // console.log(error.code)
      } else {
        // 处理非 HttpError
        // ElMessage.error('登录失败，请稍后重试')
        console.error('[Login] Unexpected error:', error)
      }
    } finally {
      refreshCaptcha()
      loading.value = false
    }
  }

  // 获取验证码
  const refreshCaptcha = async () => {
    fetchCaptcha().then((res) => {
      formData.uuid = res.uuid
      captcha.value = res.image
    })
  }

  // 登录成功提示
  const showLoginSuccessNotice = () => {
    setTimeout(() => {
      ElNotification({
        title: t('login.success.title'),
        type: 'success',
        duration: 2500,
        zIndex: 10000,
        //message: `${t('login.success.message')}, ${systemName}!`
        message: `${t('login.success.message')}, ${formData.username}!`
      })
    }, 150)
  }
</script>

<style scoped>
  @import './style.css';
</style>

<style lang="scss" scoped>
  :deep(.el-select__wrapper) {
    height: 40px !important;
  }

  .login-form {
    margin-top: 20px;

    :deep(.el-form-item) {
      margin-bottom: 16px;
    }
  }

  .login-submit-wrap {
    margin-top: 20px;
  }

  .welcome-dialog {
    line-height: 1.7;
    font-size: 14px;
    color: var(--el-text-color-regular);

    &__intro {
      margin: 0 0 16px;
      font-weight: 500;
      color: var(--el-text-color-primary);
    }

    &__list {
      margin: 0 0 16px;
      padding-left: 20px;

      li + li {
        margin-top: 10px;
      }

      a {
        color: var(--el-color-primary);
        text-decoration: none;

        &:hover {
          text-decoration: underline;
        }
      }
    }

    &__tag {
      color: var(--el-text-color-secondary);
      font-size: 13px;
    }

    &__footer {
      margin: 0 0 10px;
      color: var(--el-text-color-primary);
    }

    &__links {
      display: flex;
      gap: 16px;

      a {
        color: var(--el-color-primary);
        font-weight: 500;
        text-decoration: none;

        &:hover {
          text-decoration: underline;
        }
      }
    }
  }
</style>
